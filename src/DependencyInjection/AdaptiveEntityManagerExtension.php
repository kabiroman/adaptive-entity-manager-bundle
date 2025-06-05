<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\DependencyInjection;

use Kabiroman\AdaptiveEntityManagerBundle\Connection\DoctrineTransactionalConnection;
use Kabiroman\AdaptiveEntityManagerBundle\DataAdapter\AdapterRegistry;
use Kabiroman\AdaptiveEntityManagerBundle\DependencyInjection\Compiler\ManagerRegistryPass;
use Kabiroman\AdaptiveEntityManagerBundle\Metadata\EntityClassMetadataProvider;
use Kabiroman\AdaptiveEntityManagerBundle\Service\ManagerRegistry;
use Kabiroman\AEM\AdaptiveEntityManager;
use Kabiroman\AEM\Config;
use RuntimeException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class AdaptiveEntityManagerExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $config['entities'] = [];

        if (!isset($config['entity_managers'])) {
            throw new RuntimeException('Parameter "entity_managers" is required for configuration.');
        }

        $container->register('adaptive_entity_manager.manager_registry', ManagerRegistry::class)
            ->setArguments([
                new Reference('Psr\\EventDispatcher\\EventDispatcherInterface'),
            ]);
        $container->register('adaptive_entity_manager.adapter_registry', AdapterRegistry::class);

        foreach ($config['entity_managers'] as $name => $managerConfig) {
            $config['entities'][$name] = [];
            // Automatic loading of objects from YAML files
            $entitiesDir = $container->getParameter('kernel.project_dir') . '/config/aem/entities/';
            if (is_dir($entitiesDir)) {
                $finder = new Finder();
                $finder->files()->in($entitiesDir)->name($name.'.*.yaml');
                $entities = [];
                foreach ($finder as $file) {
                    $fileConfig = Yaml::parse($file->getContents());
                    $entities = array_merge($entities, $fileConfig);
                }
                $config['entities'][$name] = array_merge($config['entities'][$name], $entities);
            }
            $container->register('adaptive_entity_manager.'.$name.'_metadata_provider', EntityClassMetadataProvider::class)
                ->setArguments([
                    $config['entities'][$name],
                ]);

            $configDefinition = $container->register("adaptive_entity_manager.$name".'_config', Config::class)
                ->setArguments([
                    $managerConfig['entities_dir'],
                    $managerConfig['entities_namespace'],
                    $container->getParameter('kernel.cache_dir').'/aem/'.$name,
                ]);

            $connectionName = $managerConfig['connection'] ?? null;
            $definition = $container->register("adaptive_entity_manager.$name".'_entity_manager', AdaptiveEntityManager::class)
                ->setArguments([
                    $configDefinition,
                    $container->getDefinition('adaptive_entity_manager.'.$name.'_metadata_provider'),
                    $container->getDefinition('adaptive_entity_manager.adapter_registry'),
                    '$eventDispatcher' => new Reference('Psr\\EventDispatcher\\EventDispatcherInterface'),
                ]);
            if ($connectionName !== null) {
                $container->register('adaptive_entity_manager.'.$connectionName.'_connection', DoctrineTransactionalConnection::class)
                    ->setArguments([
                        new Reference('doctrine.dbal.'.$connectionName.'_connection'),
                    ]);
                $definition->addArgument($container->getDefinition('adaptive_entity_manager.'.$connectionName.'_connection'));
            }
            $definition->addTag(ManagerRegistryPass::MANAGER_TAG);
        }

        // Load services
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
    }

    public function getAlias(): string
    {
        return 'adaptive_entity_manager';
    }
}
 
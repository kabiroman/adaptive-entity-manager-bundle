<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\DependencyInjection;

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

        // Automatic loading of objects from YAML files
        $entitiesDir = $container->getParameter('kernel.project_dir') . '/config/packages/adaptive_entities/';
        if (is_dir($entitiesDir)) {
            $finder = new Finder();
            $finder->files()->in($entitiesDir)->name('*.yaml');
            $entities = [];
            foreach ($finder as $file) {
                $fileConfig = Yaml::parse($file->getContents());
                $entities = array_merge($entities, $fileConfig);
            }
            $config['entities'] = array_merge($config['entities'], $entities);
        }

        if (!isset($config['entity_managers'])) {
            throw new RuntimeException('Parameter "entity_managers" is required for ClassMetadataProvider configuration.');
        }
        $container->setParameter('adaptive_entity_manager.entities', $config['entities']);
        $container->register('adaptive_entity_manager.entity_metadata_provider', EntityClassMetadataProvider::class)
            ->setArguments([
                $config['entities'],
            ]);
        $container->register('adaptive_entity_manager.adapter_registry', AdapterRegistry::class);
        $container->register('adaptive_entity_manager.manager_registry', ManagerRegistry::class);

        foreach ($config['entity_managers'] as $name => $managerConfig) {
            $configDefinition = $container->register("adaptive_entity_manager.$name".'_config', Config::class)
                ->setArguments([
                    $managerConfig['entities_dir'],
                    $managerConfig['entities_namespace'],
                    $container->getParameter('kernel.project_dir'),
                ]);

            $connectionName = $managerConfig['connection'] ?? null;
            $definition = $container->register("adaptive_entity_manager.$name".'_entity_manager', AdaptiveEntityManager::class)
                ->setArguments([
                    $configDefinition,
                    $container->getDefinition('adaptive_entity_manager.entity_metadata_provider'),
                    $container->getDefinition('adaptive_entity_manager.adapter_registry'),
                ]);
            if ($connectionName !== null) {
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
 
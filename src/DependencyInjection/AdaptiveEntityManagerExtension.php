<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\DependencyInjection;

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

        // Validate and set parameters for ClassMetadataProvider and EntityManager
        if (!isset($config['entities_dir'])) {
            throw new \RuntimeException('Parameter "entities_dir" is required for ClassMetadataProvider configuration.');
        }
        if (!isset($config['entities_namespace'])) {
            throw new \RuntimeException('Parameter "entities_namespace" is required for ClassMetadataProvider configuration.');
        }

        $container->setParameter('adaptive_entity_manager.entities_dir', $config['entities_dir']);
        $container->setParameter('adaptive_entity_manager.entities_namespace', $config['entities_namespace']);
        $container->setParameter('adaptive_entity_manager.entities', $config['entities']);

        // Load services
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
    }

    public function getAlias(): string
    {
        return 'adaptive_entity_manager';
    }
}
 
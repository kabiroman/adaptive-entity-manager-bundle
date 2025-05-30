<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class AdaptiveEntityManagerExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Регистрируем параметры конфигурации
        $container->setParameter('adaptive_entity_manager.entity_manager', $config['entity_manager']);
        $container->setParameter('adaptive_entity_manager.entities_dir', $config['entities_dir']);
        $container->setParameter('adaptive_entity_manager.entities_namespace', $config['entities_namespace']);

        // Загружаем сервисы
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
    }

    public function getAlias(): string
    {
        return 'adaptive_entity_manager';
    }
}
 
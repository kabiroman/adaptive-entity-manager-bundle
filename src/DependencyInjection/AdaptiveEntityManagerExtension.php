<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Finder\Finder;

class AdaptiveEntityManagerExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        // Загружаем основные сервисы
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yaml');

        // Загружаем и обрабатываем основную конфигурацию
        $configuration = new Configuration();
        $mainConfig = $this->processConfiguration($configuration, $configs);

        // Инициализируем массив сущностей
        $mainConfig['entities'] = $mainConfig['entities'] ?? [];

        // Загружаем конфигурации сущностей из отдельных файлов
        $entitiesDir = $container->getParameter('kernel.project_dir') . '/config/packages/entities';
        if (is_dir($entitiesDir)) {
            $finder = new Finder();
            $finder->files()
                ->in($entitiesDir)
                ->name('adaptive_entity_manager_entity*.yaml')
                ->sortByName();

            $entitiesLoader = new YamlFileLoader($container, new FileLocator($entitiesDir));

            foreach ($finder as $file) {
                try {
                    // Загружаем конфигурацию из файла
                    $entitiesLoader->load($file->getFilename());
                    
                    // Получаем конфигурацию сущностей из файла
                    $entityConfig = $container->getParameter('adaptive_entity_manager.entities');
                    if (is_array($entityConfig)) {
                        // Извлекаем имя сущности из имени файла
                        if (preg_match('/adaptive_entity_manager_entity_([^.]+)\.yaml$/', $file->getFilename(), $matches)) {
                            $entityName = $matches[1];
                            // Если в файле нет явного указания имени сущности, используем имя из файла
                            if (count($entityConfig) === 1 && !isset($entityConfig[$entityName])) {
                                $entityData = reset($entityConfig);
                                $entityConfig = [$entityName => $entityData];
                            }
                        }
                        
                        // Объединяем конфигурации
                        $mainConfig['entities'] = array_merge(
                            $mainConfig['entities'],
                            $entityConfig
                        );
                    }
                } catch (\Exception $e) {
                    // Пропускаем файлы с ошибками, но логируем их
                    // TODO: добавить логирование
                }
            }
        }

        // Устанавливаем итоговую конфигурацию
        $container->setParameter('adaptive_entity_manager.config', $mainConfig);
    }

    public function getAlias(): string
    {
        return 'adaptive_entity_manager';
    }
}

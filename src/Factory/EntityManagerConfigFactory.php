<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\Factory;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;

class EntityManagerConfigFactory
{
    public static function create(string $bundleDir, string $cacheDir): Configuration
    {
        $config = ORMSetup::createConfiguration(
            isDevMode: true,
            proxyDir: $cacheDir . '/proxies'
        );

        // Настраиваем драйвер для атрибутов (аннотаций)
        $paths = [$bundleDir . '/src/Entity'];
        $driver = new AttributeDriver($paths);
        $config->setMetadataDriverImpl($driver);

        // Настраиваем namespace для прокси-классов
        $config->setProxyNamespace('AdaptiveEntityManagerBundle\Proxies');

        return $config;
    }
} 
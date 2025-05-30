<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\Factory;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\ORMSetup;

class EntityManagerConfigFactory
{
    public static function create(string $bundleDir, string $cacheDir): Configuration
    {
        $config = ORMSetup::createConfiguration(
            isDev: true,
            cache: null,
            attributeReader: null
        );

        $config->setMetadataDriverImpl(
            $config->newDefaultAnnotationDriver(
                [$bundleDir . '/src/Entity'],
                false
            )
        );

        $config->setProxyDir($cacheDir . '/proxies');
        $config->setProxyNamespace('AdaptiveEntityManagerBundle\Proxies');

        return $config;
    }
} 
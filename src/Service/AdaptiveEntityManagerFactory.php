<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Kabiroman\AEM\AdaptiveEntityManager;
use Kabiroman\AEM\Config;

class AdaptiveEntityManagerFactory
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly string $entitiesDir,
        private readonly string $entitiesNamespace
    ) {
    }

    public function create(): AdaptiveEntityManager
    {
        $config = new Config(
            entityFolder: $this->entitiesDir,
            entityNamespace: $this->entitiesNamespace,
            cacheFolder: sys_get_temp_dir() . '/adaptive-entity-manager'
        );
        
        return new AdaptiveEntityManager(
            $this->entityManager,
            $config
        );
    }
}

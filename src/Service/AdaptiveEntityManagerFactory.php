<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Kabiroman\AEM\AdaptiveEntityManager;
use Kabiroman\AEM\Configuration\EntityConfiguration;

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
        $config = new EntityConfiguration();
        $config->setEntityFolder($this->entitiesDir);
        $config->setEntityNamespace($this->entitiesNamespace);
        
        return new AdaptiveEntityManager(
            $this->entityManager,
            $config
        );
    }
}

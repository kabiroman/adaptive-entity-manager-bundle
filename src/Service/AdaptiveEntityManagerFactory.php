<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Kabiroman\AEM\AdaptiveEntityManager;
use Kabiroman\AEM\Config;
use Kabiroman\AdaptiveEntityManagerBundle\Connection\DoctrineTransactionalConnection;
use Kabiroman\AdaptiveEntityManagerBundle\DataAdapter\BundleEntityDataAdapterProvider;
use Kabiroman\AdaptiveEntityManagerBundle\Metadata\BundleClassMetadataProvider;

class AdaptiveEntityManagerFactory
{
    private ?AdaptiveEntityManager $instance = null;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly array $config
    ) {}

    public function create(): AdaptiveEntityManager
    {
        if ($this->instance === null) {
            // Создаем необходимые зависимости для AdaptiveEntityManager
            $aemConfig = new Config(
                entityFolder: $this->config['config']['entity_folder'],
                entityNamespace: $this->config['config']['entity_namespace'],
                cacheFolder: $this->config['config']['cache_folder']
            );
            
            $classMetadataProvider = new BundleClassMetadataProvider($this->config);
            $entityDataAdapterProvider = new BundleEntityDataAdapterProvider($this->entityManager);
            $transactionalConnection = new DoctrineTransactionalConnection($this->entityManager->getConnection());
            
            $this->instance = new AdaptiveEntityManager(
                $aemConfig,
                $classMetadataProvider,
                $entityDataAdapterProvider,
                $transactionalConnection
            );
        }

        return $this->instance;
    }
}

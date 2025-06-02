<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\Service;

use Kabiroman\AEM\AdaptiveEntityManager;
use Kabiroman\AEM\Config;
use Kabiroman\AEM\Metadata\ClassMetadataProvider;
use Kabiroman\AEM\DataAdapter\EntityDataAdapterProvider;
use Kabiroman\AEM\TransactionalConnection;

class AdaptiveEntityManagerFactory
{
    public function __construct(
        private readonly Config $config,
        private readonly ClassMetadataProvider $classMetadataProvider,
        private readonly EntityDataAdapterProvider $entityDataAdapterProvider,
        private readonly ?TransactionalConnection $transactionalConnection = null
    ) {
    }

    public function create(): AdaptiveEntityManager
    {
        return new AdaptiveEntityManager(
            $this->config,
            $this->classMetadataProvider,
            $this->entityDataAdapterProvider,
            $this->transactionalConnection
        );
    }
}

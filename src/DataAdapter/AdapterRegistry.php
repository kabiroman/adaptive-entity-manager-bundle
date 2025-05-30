<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\DataAdapter;

use Kabiroman\AEM\ClassMetadata;
use Kabiroman\AEM\DataAdapter\EntityDataAdapter;
use Kabiroman\AEM\DataAdapter\EntityDataAdapterProvider;

class AdapterRegistry implements EntityDataAdapterProvider
{
    /**
     * @var EntityDataAdapterProvider[]
     */
    private array $providers = [];

    public function addProvider(EntityDataAdapterProvider $provider): void
    {
        $this->providers[] = $provider;
    }

    public function getAdapter(ClassMetadata $metadata): EntityDataAdapter
    {
        $lastException = null;
        
        foreach ($this->providers as $provider) {
            try {
                return $provider->getAdapter($metadata);
            } catch (\RuntimeException $e) {
                $lastException = $e;
                continue;
            }
        }

        throw new \RuntimeException(
            sprintf(
                'No suitable adapter found for entity "%s". Last error: %s',
                $metadata->getName(),
                $lastException ? $lastException->getMessage() : 'No providers available'
            )
        );
    }
} 
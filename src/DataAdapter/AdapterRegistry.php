<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\DataAdapter;

use Kabiroman\AEM\ClassMetadata;
use Kabiroman\AEM\DataAdapter\EntityDataAdapter;
use Kabiroman\AEM\DataAdapter\EntityDataAdapterProvider;

class AdapterRegistry implements EntityDataAdapterProvider
{
    /**
     * @var EntityDataAdapter[]
     */
    private array $adapters = [];

    public function addAdapter(EntityDataAdapter $adapter): void
    {
        $this->adapters[] = $adapter;
    }

    public function getAdapter(ClassMetadata $metadata): EntityDataAdapter
    {
        $lastException = null;
        
        foreach ($this->adapters as $adapter) {
            try {
                return $adapter;
            } catch (\RuntimeException $e) {
                $lastException = $e;
                continue;
            }
        }

        throw new \RuntimeException(
            sprintf(
                'No suitable adapter found for entity "%s". Last error: %s',
                $metadata->getName(),
                $lastException ? $lastException->getMessage() : 'No adapters available'
            )
        );
    }
} 
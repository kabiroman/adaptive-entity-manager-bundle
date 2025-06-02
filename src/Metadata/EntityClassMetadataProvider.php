<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\Metadata;

use Kabiroman\AEM\ClassMetadata;
use Kabiroman\AEM\Metadata\ClassMetadataProvider;

class EntityClassMetadataProvider implements ClassMetadataProvider
{
    private array $entities = [];

    public function __construct(array $config)
    {
        $this->initializeMetadata($config);
    }

    private function initializeMetadata(array $config): void
    {
        foreach ($config as $entityName => $entityConfig) {
            $this->entities[$entityName] = new EntityClassMetadata($entityName, $entityConfig);
        }
    }

    public function getClassMetadata(string $entityName): ?ClassMetadata
    {
        return $this->entities[$entityName] ?? null;
    }
}

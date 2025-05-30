<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\Metadata;

use Kabiroman\AEM\ClassMetadata;
use Kabiroman\AEM\Metadata\ClassMetadataProvider;

class BundleClassMetadataProvider implements ClassMetadataProvider
{
    private array $metadata = [];

    public function __construct(array $config)
    {
        $this->initializeMetadata($config);
    }

    private function initializeMetadata(array $config): void
    {
        foreach ($config['entities'] as $entityName => $entityConfig) {
            $this->metadata[$entityConfig['class']] = new BundleClassMetadata(
                $entityConfig['class'],
                $entityConfig['fields']
            );
        }
    }

    public function getClassMetadata(string $entityName): ?ClassMetadata
    {
        return $this->metadata[$entityName] ?? null;
    }
}

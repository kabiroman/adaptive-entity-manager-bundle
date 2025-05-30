<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\Metadata;

use Kabiroman\AEM\Metadata\AbstractClassMetadata;

class BundleClassMetadata extends AbstractClassMetadata
{
    public function __construct(string $className, array $fields)
    {
        $this->metadata = [
            $className => [
                'dataAdapterClass' => 'Kabiroman\AEM\DataAdapter\DefaultEntityDataAdapter',
                'fields' => [],
                'id' => []
            ]
        ];

        foreach ($fields as $fieldName => $fieldConfig) {
            if (isset($fieldConfig['id']) && $fieldConfig['id'] === true) {
                $this->metadata[$className]['id'][$fieldName] = [
                    'type' => $fieldConfig['type'],
                    'column' => $fieldName,
                    'nullable' => $fieldConfig['options']['nullable'] ?? false
                ];
            } else {
                $this->metadata[$className]['fields'][$fieldName] = [
                    'type' => $fieldConfig['type'],
                    'column' => $fieldName,
                    'nullable' => $fieldConfig['options']['nullable'] ?? false
                ];
            }
        }
    }
}

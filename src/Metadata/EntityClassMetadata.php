<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\Metadata;

use Kabiroman\AEM\Metadata\AbstractClassMetadata;

class EntityClassMetadata extends AbstractClassMetadata
{
    public function __construct(string $className, array $parameters)
    {
        $this->metadata = [
            $className => $parameters
        ];
    }
}

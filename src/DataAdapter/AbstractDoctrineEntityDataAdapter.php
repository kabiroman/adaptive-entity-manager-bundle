<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\DataAdapter;

use Doctrine\ORM\EntityManagerInterface;
use Kabiroman\AEM\DataAdapter\EntityDataAdapter;

abstract class AbstractDoctrineEntityDataAdapter implements EntityDataAdapter
{
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}

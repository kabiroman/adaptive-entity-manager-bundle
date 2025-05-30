<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\DataAdapter;

use Doctrine\ORM\EntityManagerInterface;
use Kabiroman\AEM\ClassMetadata;
use Kabiroman\AEM\DataAdapter\EntityDataAdapter;
use Kabiroman\AEM\DataAdapter\EntityDataAdapterProvider;

abstract class AbstractDoctrineEntityDataAdapterProvider implements EntityDataAdapterProvider
{
    /**
     * @var array<string, EntityDataAdapter>
     */
    private array $adapters = [];

    public function __construct(
        protected readonly EntityManagerInterface $entityManager
    ) {
    }

    public function getAdapter(ClassMetadata $metadata): EntityDataAdapter
    {
        $className = $metadata->getName();

        if (!isset($this->adapters[$className])) {
            if (!class_exists($className)) {
                throw new \RuntimeException(sprintf('Class "%s" does not exist', $className));
            }

            try {
                $doctrineMetadata = $this->entityManager->getClassMetadata($className);
            } catch (\Exception $e) {
                throw new \RuntimeException(
                    sprintf('Class "%s" is not a valid Doctrine entity: %s', $className, $e->getMessage())
                );
            }

            $this->adapters[$className] = $this->createAdapter($className);
        }

        return $this->adapters[$className];
    }

    /**
     * Создает конкретный адаптер для сущности
     * Этот метод должен быть реализован в конкретных провайдерах
     */
    abstract protected function createAdapter(string $entityClass): EntityDataAdapter;
} 
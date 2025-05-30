<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\DataAdapter;

use Doctrine\ORM\EntityManagerInterface;
use Kabiroman\AEM\ClassMetadata;
use Kabiroman\AEM\DataAdapter\EntityDataAdapter;
use Kabiroman\AEM\DataAdapter\EntityDataAdapterProvider;

class BundleEntityDataAdapterProvider implements EntityDataAdapterProvider
{
    /**
     * @var array<string, EntityDataAdapter>
     */
    private array $adapters = [];

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function getAdapter(ClassMetadata $metadata): EntityDataAdapter
    {
        $className = $metadata->getName();

        if (!isset($this->adapters[$className])) {
            // Проверяем, что класс существует и является сущностью Doctrine
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

            // Создаем и кэшируем адаптер
            $this->adapters[$className] = new BundleEntityDataAdapter(
                $this->entityManager,
                $className
            );
        }

        return $this->adapters[$className];
    }
}

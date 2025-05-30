<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\DataAdapter;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Kabiroman\AEM\DataAdapter\EntityDataAdapter;

abstract class AbstractDoctrineEntityDataAdapter implements EntityDataAdapter
{
    protected EntityRepository $repository;
    protected ClassMetadata $metadata;

    public function __construct(
        protected readonly EntityManagerInterface $entityManager,
        protected readonly string $entityClass
    ) {
        $this->repository = $this->entityManager->getRepository($this->entityClass);
        $this->metadata = $this->entityManager->getClassMetadata($this->entityClass);
    }

    public function insert(array $row): array
    {
        $entity = $this->createEntity($row);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $this->convertEntityToArray($entity);
    }

    public function update(array $identifier, array $row): array
    {
        $entity = $this->repository->find($identifier);
        if ($entity === null) {
            throw new \RuntimeException('Entity not found');
        }

        $this->updateEntityFromArray($entity, $row);
        $this->entityManager->flush();

        return $this->convertEntityToArray($entity);
    }

    public function delete(array $identifier): void
    {
        $entity = $this->repository->find($identifier);
        if ($entity === null) {
            throw new \RuntimeException('Entity not found');
        }

        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    public function refresh(array $identifier): array
    {
        $entity = $this->repository->find($identifier);
        if ($entity === null) {
            throw new \RuntimeException('Entity not found');
        }

        $this->entityManager->refresh($entity);

        return $this->convertEntityToArray($entity);
    }

    public function loadById(array $identifier): ?array
    {
        $entity = $this->repository->find($identifier);
        if ($entity === null) {
            return null;
        }

        return $this->convertEntityToArray($entity);
    }

    public function loadAll(
        array $criteria = [],
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null
    ): array {
        $queryBuilder = $this->repository->createQueryBuilder('e');

        foreach ($criteria as $field => $value) {
            $queryBuilder->andWhere("e.$field = :$field")
                ->setParameter($field, $value);
        }

        if ($orderBy !== null) {
            foreach ($orderBy as $field => $direction) {
                $queryBuilder->addOrderBy("e.$field", $direction);
            }
        }

        if ($limit !== null) {
            $queryBuilder->setMaxResults($limit);
        }
        if ($offset !== null) {
            $queryBuilder->setFirstResult($offset);
        }

        $entities = $queryBuilder->getQuery()->getResult();

        return array_map([$this, 'convertEntityToArray'], $entities);
    }

    /**
     * Создает новую сущность из массива данных
     */
    protected function createEntity(array $data): object
    {
        $entity = new $this->entityClass();
        $this->updateEntityFromArray($entity, $data);

        return $entity;
    }

    /**
     * Обновляет сущность из массива данных
     * Этот метод можно переопределить в конкретных адаптерах для кастомной логики маппинга
     */
    protected function updateEntityFromArray(object $entity, array $data): void
    {
        foreach ($data as $field => $value) {
            if (!$this->metadata->hasField($field) && !$this->metadata->hasAssociation($field)) {
                continue;
            }

            $setter = 'set' . ucfirst($field);
            if (method_exists($entity, $setter)) {
                $entity->$setter($value);
            }
        }
    }

    /**
     * Конвертирует сущность в массив
     * Этот метод можно переопределить в конкретных адаптерах для кастомной логики маппинга
     */
    protected function convertEntityToArray(object $entity): array
    {
        $result = [];
        foreach ($this->metadata->getFieldNames() as $fieldName) {
            $getter = 'get' . ucfirst($fieldName);
            if (method_exists($entity, $getter)) {
                $result[$fieldName] = $entity->$getter();
            }
        }

        return $result;
    }
} 
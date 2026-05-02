<?php

declare(strict_types=1);

namespace Kabiroman\AdaptiveEntityManagerBundle\Tests\Kernel\Stub;

use Kabiroman\AEM\DataAdapter\EntityDataAdapter;

/**
 * Stub adapter for kernel smoke tests (no database).
 */
final class SmokeEntityDataAdapter implements EntityDataAdapter
{
    public function insert(array $row): array
    {
        return ['id' => 1];
    }

    public function update(array $identifier, array $row): void
    {
    }

    public function delete(array $identifier): void
    {
    }

    public function refresh(array $identifier): array
    {
        return [];
    }

    public function loadById(array $identifier): array|null
    {
        return null;
    }

    public function loadAll(
        array $criteria = [],
        array|null $orderBy = null,
        int|null $limit = null,
        int|null $offset = null,
    ): array {
        return [];
    }
}

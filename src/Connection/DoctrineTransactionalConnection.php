<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\Connection;

use Doctrine\DBAL\Connection;
use Kabiroman\AEM\TransactionalConnection;

class DoctrineTransactionalConnection implements TransactionalConnection
{
    public function __construct(
        private readonly Connection $connection
    ) {
    }

    public function beginTransaction(): void
    {
        if (!$this->connection->isTransactionActive()) {
            $this->connection->beginTransaction();
        }
    }

    public function commitTransaction(): void
    {
        if ($this->connection->isTransactionActive()) {
            $this->connection->commit();
        }
    }

    public function rollbackTransaction(): void
    {
        if ($this->connection->isTransactionActive()) {
            $this->connection->rollBack();
        }
    }
}

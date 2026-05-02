<?php

declare(strict_types=1);

namespace TestsFunctional\Entity\Test;

/**
 * Fixture entity for ValueObject integration tests (container compile only).
 */
class VoTestEntity
{
    private int $id = 0;

    private string $email = '';

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}

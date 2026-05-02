<?php

declare(strict_types=1);

namespace App\Entity;

/**
 * Minimal entity for kernel smoke tests only.
 */
class SmokeEntity
{
    private int $id = 0;

    private string $label = '';

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }
}

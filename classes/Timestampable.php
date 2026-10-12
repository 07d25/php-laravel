<?php

namespace App;

use DateTimeImmutable;

// Trait: permite reutilizar un bloque de comportamiento entre varias clases.
trait Timestampable
{
    protected DateTimeImmutable $createdAt;
    protected ?DateTimeImmutable $updatedAt = null;

    public function markCreated(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function touch(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}

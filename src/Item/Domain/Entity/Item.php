<?php

declare(strict_types=1);

namespace App\Item\Domain\Entity;

/**
 * Class Item
 * @package App\Item\Domain\Entity
 */
class Item
{

    public function __construct(
        private int $id, 
        private string $name)
    {
    }

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
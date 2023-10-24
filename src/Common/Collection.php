<?php

namespace AutoCode\Core\Common;

use AutoCode\Core\Interfaces\CollectionInterface;

final class Collection implements CollectionInterface
{
    private int $position = 0;
    private array $collection = [];

    public function next(): mixed
    {
        $this->position++;

        return $this->current();
    }

    public function current(): mixed
    {
        return $this->collection[$this->position] ?? false;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->collection[$this->position]);
    }

    public function rewind(): mixed
    {
        $this->position = 0;

        return $this->current();
    }

    public function add(mixed $value): mixed
    {
        return $this->collection[] = $value;
    }

    public function count(): int
    {
        return count($this->collection);
    }

    public function previous(): mixed
    {
        $this->position--;

        return $this->current();
    }
}
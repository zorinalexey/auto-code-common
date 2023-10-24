<?php

namespace AutoCode\Core\Interfaces;

use Iterator;

interface CollectionInterface extends Iterator
{
    public function add(mixed $value): mixed;

    public function count(): int;

    public function previous(): mixed;
}
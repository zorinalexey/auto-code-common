<?php

/** @noinspection MissingParameterTypeDeclarationInspection */

namespace AutoCode\Core\Interfaces;

use AutoCode\Core\Common\Model\AbstractModel;
use PDO;

interface ModelInterface
{
    public function update(): self|false;

    public function delete(): bool;

    public function find(array|string|int $data = null): ?self;

    public function save(): self|false;

    public function sync(): bool;

    public function getChildren(): CollectionInterface;

    public function getTableName(): string;

    public function getDbConnect(): PDO;

    /**
     * @param  string  $name
     * @return self|null
     */
    public function __get($name): mixed;

    /**
     * @param  string  $name
     * @param  array  $arguments
     * @return self|null
     */
    public function __call($name, $arguments): mixed;

    public function __toString(): string;

    public function create(): AbstractModel|self|false;

    public function getFillable(): array;

    public function getHidden(): array;
}

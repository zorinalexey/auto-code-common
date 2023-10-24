<?php /** @noinspection MissingParameterTypeDeclarationInspection */

namespace AutoCode\Utils\Interfaces;

use PDO;

interface ModelInterface
{
    public function update(): self|false;

    public function delete(): bool;

    public function find(array|string|int|null $data = null): self|null;

    public function save(): self|false;

    public function sync(): bool;

    public function getChildren(): CollectionInterface;

    public function getTableName(): string;

    public function getDbConnect(): PDO;

    /**
     * @param string $name
     * @return self|null
     */
    public function __get($name): mixed;

    /**
     * @param string $name
     * @param array $arguments
     * @return self|null
     */
    public function __call($name, $arguments): mixed;

    public function __toString(): string;

    public function load(): CollectionInterface;

    public function create(): self|null;

    public function getFillable(): array;

    public function getHidden(): array;

}
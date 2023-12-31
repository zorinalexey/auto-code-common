<?php

namespace AutoCode\Core\Common\QueryBuilder;

use AutoCode\Core\Common\Model\AbstractModel;
use AutoCode\Core\Interfaces\ModelInterface;
use AutoCode\Core\Interfaces\QueryBuilderInterface;

abstract class AbstractModelQueryBuilder implements QueryBuilderInterface
{
    private static int $setParamCount = 0;

    protected ?string $table = null;

    protected array $binds = [];

    final public function table(string $table): SelectModel|DeleteModel|CreateModel|UpdateModel|self
    {
        $this->table = $table;

        return $this;
    }

    final public function set(array|AbstractModel|ModelInterface $data): QueryBuilderInterface
    {
        self::$setParamCount++;

        foreach ($data as $field => $value) {
            $count = count($this->binds);
            if ($value !== null && ! is_bool($value)) {
                $value = (string) $value;
            }
            $bindName = ":param{$count}{$this->table}".self::$setParamCount;
            $this->binds[$field] = [$bindName => $value];
        }

        return $this;
    }

    abstract public function __toString(): string;

    final public function getBindParams(): array
    {
        $binds = [];

        foreach ($this->binds as $bind) {
            foreach ($bind as $bindName => $value) {
                $binds[$bindName] = $value;
            }
        }

        return $binds;
    }
}

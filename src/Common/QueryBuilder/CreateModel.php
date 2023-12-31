<?php

namespace AutoCode\Core\Common\QueryBuilder;

final class CreateModel extends AbstractModelQueryBuilder
{
    public function __toString(): string
    {
        $str = "INSERT INTO {$this->table} SET (";

        foreach (array_keys($this->binds) as $field) {
            $str .= "{$this->table}.{$field}, ";
        }

        $str = trim($str, ', ').') VALUES (';

        foreach ($this->getBindParams() as $k => $v) {
            $str .= "{$k}, ";
        }

        return trim($str, ', ').');';
    }
}

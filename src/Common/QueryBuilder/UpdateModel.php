<?php

namespace AutoCode\Core\Common\QueryBuilder;

final class UpdateModel extends AbstractModelQueryBuilder
{
    public function __toString(): string
    {
        $bindId = array_keys($this->binds['id'])[0];
        $str = "UPDATE {$this->table} WHERE {$this->table}.id = {$bindId} SET ";

        foreach ($this->binds as $field => $bindData) {
            $bind = array_keys($bindData);
            $str .= "{$this->table}.{$field} = {$bind[0]}, ";
        }

        return trim($str, ', ').';';
    }
}

<?php

namespace AutoCode\Utils\Common\QueryBuilder;

use AutoCode\Utils\Interfaces\QueryBuilderInterface;

class CreateModel implements QueryBuilderInterface
{

    public function table(string $table): SelectModel|DeleteModel|CreateModel|UpdateModel
    {
        // TODO: Implement table() method.
    }

    public function set(array $data): QueryBuilderInterface
    {
        // TODO: Implement set() method.
    }
}
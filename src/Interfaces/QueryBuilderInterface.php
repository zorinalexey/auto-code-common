<?php

namespace AutoCode\Core\Interfaces;

use AutoCode\Core\Common\QueryBuilder\AbstractModelQueryBuilder;
use AutoCode\Core\Common\QueryBuilder\CreateModel;
use AutoCode\Core\Common\QueryBuilder\DeleteModel;
use AutoCode\Core\Common\QueryBuilder\SelectModel;
use AutoCode\Core\Common\QueryBuilder\UpdateModel;

interface QueryBuilderInterface
{
    public function table(string $table): SelectModel|DeleteModel|CreateModel|UpdateModel|AbstractModelQueryBuilder;
    public function set(array $data): self;
    public function __toString(): string;
    public function getBindParams(): array;
}
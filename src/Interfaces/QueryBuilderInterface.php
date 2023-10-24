<?php

namespace AutoCode\Utils\Interfaces;

use AutoCode\Utils\Common\QueryBuilder\AbstractModelQueryBuilder;
use AutoCode\Utils\Common\QueryBuilder\CreateModel;
use AutoCode\Utils\Common\QueryBuilder\DeleteModel;
use AutoCode\Utils\Common\QueryBuilder\SelectModel;
use AutoCode\Utils\Common\QueryBuilder\UpdateModel;

interface QueryBuilderInterface
{
    public function table(string $table): SelectModel|DeleteModel|CreateModel|UpdateModel|AbstractModelQueryBuilder;
    public function set(array $data): self;
    public function __toString(): string;
    public function getBindParams(): array;
}
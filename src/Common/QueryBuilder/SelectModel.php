<?php

namespace AutoCode\Utils\Common\QueryBuilder;

use AutoCode\Utils\Interfaces\QueryBuilderInterface;

final class SelectModel implements QueryBuilderInterface
{
    public function table(string $table): self
    {
        return $this;
    }

    public function where(array|int|string $data): self
    {
        return $this;
    }

    public function set(array $data): self
    {
        return $this;
    }
}
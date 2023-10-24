<?php

namespace AutoCode\Utils\Common\QueryBuilder\Types;

interface TypeInterface
{
    public function __construct(string|null $data = null);

    public function __toString():string;
}
<?php

namespace AutoCode\Core\Common\QueryBuilder\Types;

use DateTime;

final class Date extends DateTime implements TypeInterface
{
    public function __construct(string|null $date = null)
    {
        parent::__construct($date);
    }

    public function __toString():string
    {
        return $this->format('Y-m-d H:i:s');
    }
}
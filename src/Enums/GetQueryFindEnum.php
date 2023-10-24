<?php

namespace AutoCode\Core\Enums;

/**
 * @property  string $name
 * @property  string $value
 */
enum GetQueryFindEnum: string
{
    case ONE = 'fetch';
    case ALL = 'fetchAll';
}

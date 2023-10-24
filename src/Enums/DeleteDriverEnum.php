<?php

namespace AutoCode\Core\Enums;

/**
 * @property string $name
 * @property string $value
 */
enum DeleteDriverEnum: string
{
    case SOFT = 'softDelete';
    case HARD = 'hardDelete';
}

<?php

namespace AutoCode\Utils\Interfaces;

interface FileInterface extends StorageInterface
{
    public function write(): bool;

}
<?php

namespace AutoCode\Core\Interfaces;

interface FileInterface extends StorageInterface
{
    public function write(): bool;

}
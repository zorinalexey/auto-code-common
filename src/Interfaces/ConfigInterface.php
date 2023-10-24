<?php

namespace AutoCode\Utils\Interfaces;

interface ConfigInterface
{
    public function load(string|FileInterface $path): self;

    public function set(string $key, mixed $value): self;

    public function get(string $key, mixed $default = null): mixed;
}
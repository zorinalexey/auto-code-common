<?php

namespace AutoCode\Utils\Interfaces;

use AutoCode\Utils\Common\Stat;
use AutoCode\Utils\Enums\ChmodEnum;
use AutoCode\Utils\Enums\FileTypeEnum;

interface StorageInterface
{
    public static function init(string|null $path): self;

    public function has(): bool;

    public function create(): bool;

    public function chmod(ChmodEnum $chmod): bool;

    public function chgrp(string|int $grp): bool;

    public function chown(string|int $own): bool;

    public function copy(string $path): bool;

    public function delete(): bool;

    public function checkWrite(): bool;

    public function checkRead(): bool;

    public function getType(): FileTypeEnum;

    public function read(): mixed;

    public function stat(): Stat;

    public function getParent(): DirInterface;
}
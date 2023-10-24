<?php

namespace AutoCode\Core\Interfaces;

use AutoCode\Core\Common\Stat;
use AutoCode\Core\Enums\ChmodEnum;
use AutoCode\Core\Enums\FileTypeEnum;

interface StorageInterface
{
    public static function init(?string $path): self;

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

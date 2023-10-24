<?php

namespace AutoCode\Utils\Common\QueryBuilder\Types;

final class Uuid implements TypeInterface
{
    private string|null $uuid = null;

    public function __construct(mixed $data = null)
    {
        $this->uuid = $data;
    }

    public function get(): string|null
    {
        return $this->uuid;
    }

    public function __toString(): string
    {
        if($this->uuid){
            return $this->uuid;
        }

        return $this->uuidGen();
    }

    private function uuidGen():string
    {
        $str = md5(print_r($this, true) . time() . rand(1000, 10000));

        return preg_replace('~^(\w{8})(\w{4})(\w{4})(\w{4})(\w{12})$~ui', '$1-$2-$3-$4-$5', $str);
    }
}
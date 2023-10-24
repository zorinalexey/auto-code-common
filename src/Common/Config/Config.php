<?php

namespace AutoCode\Utils\Common\Config;

use AutoCode\Utils\Interfaces\ConfigInterface;
use AutoCode\Utils\Interfaces\FileInterface;

if(!defined('ROOT_PATH')){
    define('ROOT_PATH', dirname(__FILE__, 4));
}

final class Config implements ConfigInterface
{
    private static array $instance = [];
    /**
     * @var array|mixed
     */
    private mixed $config = [];

    private function __construct()
    {

    }

    public static function getInstance(string $configName): mixed
    {
        if (isset(self::$instance[$configName])) {
            return self::$instance[$configName]->config;
        }

        self::$instance[$configName] = new self();
        $file = self::$instance[$configName]->getConfigPath() . DIRECTORY_SEPARATOR . "{$configName}.php";

        if (is_file($file)) {
            self::$instance[$configName]->config = require $file;
        }

        return self::$instance[$configName]->config;
    }

    private function getConfigPath(): string
    {
        return ROOT_PATH . DIRECTORY_SEPARATOR . 'config';
    }

    private function __clone()
    {

    }

    public function load(FileInterface|string $path): self
    {

        return $this;
    }

    public function set(string $key, mixed $value): self
    {
        $this->config[$key] = $value;

        return $this;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->config[$key]??$default;
    }
}
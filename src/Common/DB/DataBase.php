<?php

namespace AutoCode\Core\Common\DB;

use AutoCode\Core\Common\Config\Config;
use AutoCode\Core\Interfaces\QueryBuilderInterface;
use PDO;
use PDOStatement;

final class DataBase extends PDO
{
    private static array $instance = [];

    protected array $connect = [];

    private ?string $connectName = null;

    private function __construct(string $connectName)
    {
        $conf = Config::getInstance('data_base')[$connectName] ?? [];
        $this->connectName = $connectName;
        $this->connect[$this->connectName] = parent::__construct(...$conf);
    }

    public static function getInstance(string $connectName): self
    {
        return self::$instance[$connectName] ?? (self::$instance[$connectName] = new self($connectName));
    }

    public function get(QueryBuilderInterface $query): PDOStatement
    {
        $stmt = $this->connect[$this->connectName]->prepare((string) $query, PDO::FETCH_ASSOC);
        $stmt->execute($query->getBindParams());

        return $stmt;
    }

    private function __clone()
    {

    }
}

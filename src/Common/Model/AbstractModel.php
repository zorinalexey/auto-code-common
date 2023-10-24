<?php /** @noinspection ALL */

namespace AutoCode\Core\Common\Model;

use AutoCode\Core\Common\Collection;
use AutoCode\Core\Common\DB\DataBase;
use AutoCode\Core\Common\Model\Traits\DeleteTrait;
use AutoCode\Core\Common\Model\Traits\RelationsTrait;
use AutoCode\Core\Common\QueryBuilder\CreateModel;
use AutoCode\Core\Common\QueryBuilder\SelectModel;
use AutoCode\Core\Common\QueryBuilder\Types\Date;
use AutoCode\Core\Common\QueryBuilder\Types\TypeInterface;
use AutoCode\Core\Common\QueryBuilder\Types\Uuid;
use AutoCode\Core\Common\QueryBuilder\UpdateModel;
use AutoCode\Core\Enums\GetQueryFindEnum;
use AutoCode\Core\Interfaces\CollectionInterface;
use AutoCode\Core\Interfaces\ModelInterface;
use PDO;
use stdClass;

abstract class AbstractModel extends stdClass implements ModelInterface
{
    use DeleteTrait, RelationsTrait;

    private static array $mainFiellable = [
        'id' => Uuid::class,
        'date_create' => Date::class,
        'date_update' => Date::class,
        'date_delete' => Date::class,
    ];
    public Uuid|string|null $id = null;
    public Date|string|null $date_create = null;
    public Date|string|null $date_update = null;
    public Date|string|null $date_delete = null;
    protected array $load = [];
    protected array $hidden = [];
    protected string $connection = 'default';
    private array $attributes = [];
    private string|null $action = null;
    private CollectionInterface|null $children = null;

    final public function find(int|array|string|null $data = null, GetQueryFindEnum $get = GetQueryFindEnum::ONE): self|null
    {
        $queryBuilder = (new SelectModel())->table($this->getTableName());

        if ($data === null && $this->id) {
            $this->load($queryBuilder);
            $queryBuilder->where('id', $this->id, '=');
        }

        if (is_string($data) || is_int($data)) {
            $queryBuilder->where('id', $data, '=');
        }

        if (is_array($data)) {
            foreach ($data as $v) {
                $queryBuilder->where($v[0], $v[1], $v[2] ?? '=');
            }
        }

        $queryBuilder->where('date_delete', null, 'NOT NULL');
        $obj = $this->getDbConnect()->get($queryBuilder)->{$get->value}(PDO::FETCH_CLASS, static::class);

        return $obj->unsetHidenFields($obj);
    }

    final public function getTableName(): string
    {
        return mb_strtolower($this->tableName ?? preg_replace('~^(.+)/(\w+)~', '$2', str_replace('\\', '/', static::class)));
    }

    final public function load( $queryBuilder)
    {
    }

    public function getDbConnect(string|null $connectName = null): DataBase
    {
            $connectName ?? $connectName = $this->connection;

        return DataBase::getInstance($connectName);
    }

    private function hiddenFields(self|null $model)
    {
        if (!$model) {
            return $model;
        }

        foreach ($model->getHidden() as $field) {
            if (isset($model->$field)) {
                unset($model->$field);
            }
        }

        return $model->setFieldType();
    }

    public function getHidden(): array
    {
        return $this->hidden;
    }

    private function setFieldType(): static
    {
        $filable = $this->getFillable();

        foreach ($filable as $field => $type) {
            if (class_exists($type) && $type instanceof TypeInterface) {
                $this->$field = new $type($this->$field);
            }
        }

        return $this;
    }

    public function getFillable(): array
    {
        $filable = self::$mainFiellable;

        if (isset(static::$fillable)) {
            $filable = array_merge(self::$mainFiellable, static::$fillable);
        }

        return $filable;
    }

    final public function save(): self|false
    {
        if ($this->id?->get()) {
            return $this->update();
        }

        return $this->create();
    }

    public function update(): self|false
    {
        $this->action = 'update';
        $this->date_update = new Date();
        $queryBuilder = (new UpdateModel())->table($this->getTableName())->set($this);
        #$obj = $this->getDbConnect()->get($queryBuilder)->{GetQueryFindEnum::ONE}(PDO::FETCH_CLASS, static::class);
        
        return false;$obj?->unsetHidenFields($obj)?:false;
    }

    final public function create(): ModelInterface|self|false
    {
        $this->action = 'create';
        $this->id = new Uuid();
        $this->date_update = new Date();
        $this->date_create = new Date();
        $queryBuilder = (new CreateModel())->table($this->getTableName())->set($this);
        (string)$queryBuilder;
        #$obj = $this->getDbConnect()->get($queryBuilder)->{GetQueryFindEnum::ALL}(PDO::FETCH_CLASS, static::class);

        return false; $obj?->unsetHidenFields($obj)?:false;
    }

    final public function sync(): bool
    {
        foreach ($this->children as $child) {
            if ($child instanceof ModelInterface) {
                if (!$child->{$this->action}()) {
                    return false;
                }
            }
        }

        return true;
    }

    final public function getChildren(): CollectionInterface
    {
        $collection = new Collection();

        foreach (self::$attributes as $v) {
            if ($v instanceof ModelInterface) {
                $collection->add($v);
            }
        }

        return $this->children ?: $this->children = $collection;
    }

    final public function __get($name): mixed
    {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }

        if (method_exists($this, $name)) {
            $this->attributes[$name] = $this->$name();
        }

        return $this->attributes[$name] ?? ($this->attributes[$name] = null);
    }

    final public function __call($name, $arguments): mixed
    {
        if (method_exists($this, $name)) {
            return $this->$name(...$arguments);
        }

        return null;
    }

    final public function __toString(): string
    {
        $data = [
            $this,
            ...$this->attributes
        ];

        return json_encode($data, JSON_THROW_ON_ERROR);
    }

}
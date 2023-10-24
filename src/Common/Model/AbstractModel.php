<?php /** @noinspection ALL */

namespace AutoCode\Utils\Common\Model;

use AutoCode\Utils\Common\Collection;
use AutoCode\Utils\Common\DB\DataBase;
use AutoCode\Utils\Common\Model\Traits\DeleteTrait;
use AutoCode\Utils\Common\Model\Traits\RelationsTrait;
use AutoCode\Utils\Common\QueryBuilder\CreateModel;
use AutoCode\Utils\Common\QueryBuilder\SelectModel;
use AutoCode\Utils\Common\QueryBuilder\UpdateModel;
use AutoCode\Utils\Enums\GetQueryFindEnum;
use AutoCode\Utils\Interfaces\CollectionInterface;
use AutoCode\Utils\Interfaces\ModelInterface;
use AutoCode\Utils\Interfaces\QueryBuilderInterface;
use DateTime;
use PDO;
use stdClass;

abstract class AbstractModel extends stdClass implements ModelInterface
{
    use DeleteTrait, RelationsTrait;

    private static array $mainFiellable = [
        'id' => 'uuid',
        'date_create' => DateTime::class,
        'date_update' => DateTime::class,
        'date_delete' => DateTime::class,
    ];
    public string|null $id = null;
    public DateTime|string|null $date_create = null;
    public DateTime|string|null $date_update = null;
    public DateTime|string|null $date_delete = null;
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
        return $this->tableName ?? basename(static::class);
    }

    final public function load(QueryBuilderInterface $queryBuilder): QueryBuilderInterface
    {
        foreach ($this->load as $attribute) {
            $queryBuilder->join($attribute);
        }

        return $queryBuilder;
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
            if (class_exists($type)) {
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
        if ($this->id) {
            return $this->update();
        }

        return $this->create();
    }

    public function update(): self|false
    {
        $this->action = 'update';
        $this->date_update = new DateTime();
        $queryBuilder = (new UpdateModel())->table($this->getTableName())->set($this);
        $obj = $this->getDbConnect()->get($queryBuilder)->{GetQueryFindEnum::ONE}(PDO::FETCH_CLASS, static::class);
        
        return $obj->unsetHidenFields($obj);
    }

    final public function create(): self|false
    {
        $this->action = 'create';
        $this->id = $this->setId();
        $this->date_update = new DateTime();
        $this->date_create = new DateTime();
        $queryBuilder = (new CreateModel())->table($this->getTableName())->set($this);
        $obj = $this->getDbConnect()->get($queryBuilder)->{GetQueryFindEnum::ALL}(PDO::FETCH_CLASS, static::class);

        return $obj->unsetHidenFields($obj);
    }

    protected function setId(): string
    {
        $str = md5(print_r($this, true) . time() . rand(1000, 10000));

        return preg_replace('~^(\w{8})(\w{4})(\w{4})(\w{4})(\w{12})$~ui', '$1-$2-$3-$4-$5', $str);
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
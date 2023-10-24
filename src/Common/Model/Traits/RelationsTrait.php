<?php

namespace AutoCode\Utils\Common\Model\Traits;

use AutoCode\Utils\Common\Collection;
use AutoCode\Utils\Enums\GetQueryFindEnum;
use AutoCode\Utils\Interfaces\CollectionInterface;
use AutoCode\Utils\Interfaces\ModelInterface;

trait RelationsTrait
{
    final protected function hasOne(ModelInterface|string $model, string|null $foreignKey = null, string $localKey = 'id'): ModelInterface|null
    {
        if (!$foreignKey) {
            $foreignKey = $this->getTableName() . '_id';
        }

        if (is_string($model) && class_exists($model)) {
            $model = new $model();
        }

        assert($model instanceof ModelInterface);

        return $model->find([$foreignKey, $this->$foreignKey], GetQueryFindEnum::ONE);
    }

    final protected function hasMany(ModelInterface|string $model, string|null $foreignKey = null, string $localKey = 'id'): CollectionInterface
    {
        if (!$foreignKey) {
            $foreignKey = $this->getTableName() . '_id';
        }

        $collection = new Collection();

        if (is_string($model) && class_exists($model)) {
            $model = new $model();
        }

        assert($model instanceof ModelInterface);

        foreach ($model->find([$foreignKey, $this->$foreignKey], GetQueryFindEnum::ALL) as $item) {
            $collection->add($item);
        }

        return $collection;
    }
}
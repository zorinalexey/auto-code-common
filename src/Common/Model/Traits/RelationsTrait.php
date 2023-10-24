<?php

namespace AutoCode\Core\Common\Model\Traits;

use AutoCode\Core\Common\Collection;
use AutoCode\Core\Enums\GetQueryFindEnum;
use AutoCode\Core\Interfaces\CollectionInterface;
use AutoCode\Core\Interfaces\ModelInterface;

trait RelationsTrait
{
    final protected function hasOne(ModelInterface|string $model, string $foreignKey = null, string $localKey = 'id'): ?ModelInterface
    {
        if (! $foreignKey) {
            $foreignKey = $this->getTableName().'_id';
        }

        if (is_string($model) && class_exists($model)) {
            $model = new $model();
        }

        assert($model instanceof ModelInterface);

        return $model->find([$foreignKey, $this->$foreignKey], GetQueryFindEnum::ONE);
    }

    final protected function hasMany(ModelInterface|string $model, string $foreignKey = null, string $localKey = 'id'): CollectionInterface
    {
        if (! $foreignKey) {
            $foreignKey = $this->getTableName().'_id';
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

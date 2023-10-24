<?php

namespace AutoCode\Core\Common\Model\Traits;

use AutoCode\Core\Common\QueryBuilder\DeleteModel;
use AutoCode\Core\Common\QueryBuilder\Types\Date;
use AutoCode\Core\Enums\DeleteDriverEnum;

trait DeleteTrait
{
    protected DeleteDriverEnum $deleteDriver = DeleteDriverEnum::SOFT;

    final public function delete(): bool
    {
        $this->action = $this->deleteDriver->value;

        return (bool) $this->{$this->action}();
    }

    private function hardDelete(): bool
    {
        $queryBuilder = (new DeleteModel())->table($this->getTableName())->set($this);

        return (bool) $this->getDbConnect()->get($queryBuilder);
    }

    private function softDelete(): bool
    {
        $this->date_delete = new Date();

        return (bool) $this->update();
    }
}

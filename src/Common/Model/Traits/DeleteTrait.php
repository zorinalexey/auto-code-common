<?php

namespace AutoCode\Utils\Common\Model\Traits;

use AutoCode\Utils\Common\QueryBuilder\DeleteModel;
use AutoCode\Utils\Enums\DeleteDriverEnum;
use DateTime;

trait DeleteTrait
{
    protected DeleteDriverEnum $deleteDriver = DeleteDriverEnum::SOFT;

    final public function delete(): bool
    {
        $this->action = $this->deleteDriver->value;

        return (bool)$this->{$this->action}();
    }

    private function hardDelete(): bool
    {
        $queryBuilder = (new DeleteModel())->table($this->getTableName())->set($this);

        return (bool)$this->getDbConnect()->get($queryBuilder);
    }

    private function softDelete(): bool
    {
        $this->date_delete = new DateTime();

        return (bool)$this->update();
    }


}
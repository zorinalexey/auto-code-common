<?php

declare(strict_types=1);
/**
 * This file is part of the PhpAccessor package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PhpAccessor\Processor\Method;

use PhpParser\BuilderFactory;
use PhpParser\Node\Stmt\ClassMethod;

class SetterMethod extends AbstractAccessorMethod
{
    protected string $name = 'setter';

    /** @var string[] */
    private array $parameterTypes = [];

    public function setParameterTypes(array $parameterTypes): SetterMethod
    {
        $this->parameterTypes = $parameterTypes;
        return $this;
    }

    public function buildMethod(): ClassMethod
    {
        $builder = new BuilderFactory();

        $param = $builder->param($this->fieldMetadata->getFieldName());
        $param->setType(implode('|', $this->parameterTypes));

        $method = $builder->method($this->methodName);
        $method->makePublic()
            ->addParam($param)
            ->setReturnType(implode('|', $this->returnTypes));
        foreach ($this->body as $stmt) {
            $method->addStmt($stmt);
        }

        return $method->getNode();
    }
}

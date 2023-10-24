<?php

declare(strict_types=1);
/**
 * This file is part of the PhpAccessor package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PhpAccessor\Processor\Method\Generator\Getter;

use PhpAccessor\Processor\AttributeProcessor;
use PhpAccessor\Processor\Method\AccessorMethodInterface;
use PhpAccessor\Processor\Method\FieldMetadata;
use PhpAccessor\Processor\Method\Generator\GeneratorInterface;
use PhpAccessor\Processor\Method\GetterMethod;
use PhpParser\BuilderFactory;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Stmt\Return_;

class GetterBodyGenerator implements GeneratorInterface
{
    public function __construct(
        protected AttributeProcessor $attributeProcessor
    ) {
    }

    /**
     * @param GetterMethod $accessorMethod
     */
    public function generate(FieldMetadata $fieldMetadata, AccessorMethodInterface $accessorMethod): void
    {
        $builder = new BuilderFactory();
        if ($this->attributeProcessor->isDefaultNull($fieldMetadata->getFieldName())) {
            $accessorMethod->setBody(
                [
                    new Return_(
                        new Coalesce(
                            $builder->propertyFetch($builder->var('this'), $fieldMetadata->getFieldName()),
                            $builder->constFetch('null'),
                        )
                    ),
                ]
            );
        } else {
            $accessorMethod->setBody(
                [
                    new Return_(
                        $builder->propertyFetch($builder->var('this'), $fieldMetadata->getFieldName())
                    ),
                ]
            );
        }
    }
}

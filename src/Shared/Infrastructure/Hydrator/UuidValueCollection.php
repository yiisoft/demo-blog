<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Hydrator;

use Attribute;
use Yiisoft\Hydrator\Attribute\Parameter\ParameterAttributeInterface;
use Yiisoft\Hydrator\Attribute\Parameter\ParameterAttributeResolverInterface;
use Yiisoft\Hydrator\AttributeHandling\ParameterAttributeResolveContext;
use Yiisoft\Hydrator\Result;

use function is_string;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER | Attribute::IS_REPEATABLE)]
final readonly class UuidValueCollection implements ParameterAttributeInterface, ParameterAttributeResolverInterface
{
    /**
     * @param class-string<UuidValue> $class
     */
    public function __construct(
        private string $class,
    ) {}

    public function getParameterValue(
        ParameterAttributeInterface $attribute,
        ParameterAttributeResolveContext $context,
    ): Result {
        if (!$context->isResolved()) {
            return Result::fail();
        }

        $resolvedValue = $context->getResolvedValue();
        if (!is_iterable($resolvedValue)) {
            return Result::success([]);
        }

        $collection = [];
        foreach ($resolvedValue as $value) {
            if (!is_string($value)) {
                continue;
            }
            $item = $this->class::tryFromString($value);
            if ($item === null) {
                continue;
            }
            $collection[] = $item;
        }
        return Result::success($collection);
    }

    public function getResolver(): self
    {
        return $this;
    }
}

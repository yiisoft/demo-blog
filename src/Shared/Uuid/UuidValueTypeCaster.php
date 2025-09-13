<?php

declare(strict_types=1);

namespace App\Shared\Uuid;

use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;
use Yiisoft\ErrorHandler\Exception\UserException;
use Yiisoft\Hydrator\Result;
use Yiisoft\Hydrator\TypeCaster\TypeCastContext;
use Yiisoft\Hydrator\TypeCaster\TypeCasterInterface;

use function is_object;
use function is_string;

final readonly class UuidValueTypeCaster implements TypeCasterInterface
{
    public function __construct(
        public bool $throwUserException = false,
    ) {}

    /**
     * @throws UserException
     */
    public function cast(mixed $value, TypeCastContext $context): Result
    {
        $class = $this->tryGetClass($context);
        if ($class === null) {
            return Result::fail();
        }

        if (is_string($value)) {
            $result = $class::tryFromString($value);
            return $result === null ? $this->failOrUserException($context) : Result::success($result);
        }

        if (is_object($value) && $value::class === $class) {
            return Result::success($value);
        }

        return $this->failOrUserException($context);
    }

    /**
     * @throws UserException
     */
    private function failOrUserException(TypeCastContext $context): Result
    {
        if ($this->throwUserException) {
            $reflection = $context->getReflection();
            if ($reflection instanceof ReflectionParameter && $reflection->isOptional()) {
                return Result::fail();
            }
        }
        throw new UserException('Invalid ID.');
    }

    /**
     * @return class-string<UuidValue>|null
     */
    private function tryGetClass(TypeCastContext $context): string|null
    {
        $type = $context->getReflectionType();
        if ($type instanceof ReflectionNamedType) {
            return is_a($type->getName(), UuidValue::class, true) ? $type->getName() : null;
        }
        if ($type instanceof ReflectionUnionType) {
            foreach ($type->getTypes() as $subType) {
                if ($subType instanceof ReflectionNamedType && is_a($subType->getName(), UuidValue::class, true)) {
                    return $subType->getName();
                }
            }
        }
        return null;
    }
}

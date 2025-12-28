<?php

declare(strict_types=1);

namespace App\Presentation\Site\Shared\ResponseFactory\ValidateOrNotFound;

use App\Presentation\Site\Shared\ResponseFactory\PageNotFoundException;
use ReflectionException;
use Yiisoft\Hydrator\Attribute\Parameter\ParameterAttributeInterface;
use Yiisoft\Hydrator\Attribute\Parameter\ParameterAttributeResolverInterface;
use Yiisoft\Hydrator\AttributeHandling\ParameterAttributeResolveContext;
use Yiisoft\Hydrator\Result;
use Yiisoft\Validator\ValidatorInterface;

final readonly class ValidateOrNotFoundResolver implements ParameterAttributeResolverInterface
{
    /**
     * @param ValidatorInterface $validator Validator to use.
     */
    public function __construct(
        private ValidatorInterface $validator,
    ) {}

    /**
     * @throws ReflectionException
     * @throws PageNotFoundException
     */
    public function getParameterValue(
        ParameterAttributeInterface $attribute,
        ParameterAttributeResolveContext $context,
    ): Result {
        /** @var ValidateOrNotFound $attribute */

        $parameterName = $context->getParameter()->getName();
        $result = $this->validator->validate(
            $context->isResolved() ? [$parameterName => $context->getResolvedValue()] : [],
            [$parameterName => $attribute->rules],
        );

        if (!$result->isValid()) {
            throw new PageNotFoundException();
        }

        return Result::fail();
    }
}

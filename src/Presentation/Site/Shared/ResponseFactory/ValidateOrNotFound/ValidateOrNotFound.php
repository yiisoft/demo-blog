<?php

declare(strict_types=1);

namespace App\Presentation\Site\Shared\ResponseFactory\ValidateOrNotFound;

use Attribute;
use Yiisoft\Hydrator\Attribute\Parameter\ParameterAttributeInterface;
use Yiisoft\Validator\RuleInterface;

#[Attribute(Attribute::TARGET_PARAMETER | Attribute::IS_REPEATABLE)]
final readonly class ValidateOrNotFound implements ParameterAttributeInterface
{
    /**
     * @var list<RuleInterface> $rules Validation rules.
     */
    public array $rules;

    /**
     * @param RuleInterface ...$rules Validation rules.
     *
     * @no-named-arguments
     */
    public function __construct(RuleInterface ...$rules)
    {
        $this->rules = $rules;
    }

    public function getResolver(): string
    {
        return ValidateOrNotFoundResolver::class;
    }
}

<?php

declare(strict_types=1);

namespace App\Presentation\Api\Blog\Post\List;

use App\Domain\Category\CategoryId;
use Yiisoft\Hydrator\Validator\Attribute\Validate;
use Yiisoft\Input\Http\AbstractInput;
use Yiisoft\Input\Http\Attribute\Data\FromQuery;
use Yiisoft\Validator\EmptyCondition\WhenMissing;
use Yiisoft\Validator\Rule\Integer;
use Yiisoft\Validator\Rule\Uuid;

#[FromQuery]
final class Input extends AbstractInput
{
    /** @var positive-int */
    #[Integer(min: 1)]
    public int $page = 1;

    #[Validate(new Uuid(message: 'Invalid category ID format.', skipOnEmpty: new WhenMissing()))]
    public ?CategoryId $categoryId = null;
}

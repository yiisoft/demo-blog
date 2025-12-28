<?php

declare(strict_types=1);

namespace App\Application\Category\CreateCategory;

use App\Domain\Category\CategoryId;

final readonly class Result
{
    public function __construct(
        public CategoryId $id,
    ) {}
}

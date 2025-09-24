<?php

declare(strict_types=1);

namespace App\Blog\Application\CreateCategory;

use App\Blog\Domain\Category\CategoryId;

final readonly class Result
{
    public function __construct(
        public CategoryId $id,
    ) {}
}

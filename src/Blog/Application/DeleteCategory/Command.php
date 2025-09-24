<?php

declare(strict_types=1);

namespace App\Blog\Application\DeleteCategory;

use App\Blog\Domain\Category\CategoryId;

final readonly class Command
{
    public function __construct(
        public CategoryId $id,
    ) {}
}

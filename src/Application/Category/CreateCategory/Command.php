<?php

declare(strict_types=1);

namespace App\Application\Category\CreateCategory;

use App\Domain\Category\CategoryName;
use App\Domain\Category\CategorySlug;

final readonly class Command
{
    public function __construct(
        public CategoryName $name,
        public string $description,
        public CategorySlug $slug,
    ) {}
}

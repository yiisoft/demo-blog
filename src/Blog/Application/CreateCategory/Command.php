<?php

declare(strict_types=1);

namespace App\Blog\Application\CreateCategory;

use App\Blog\Domain\Category\CategoryName;
use App\Blog\Domain\Category\CategorySlug;

final readonly class Command
{
    public function __construct(
        public CategoryName $name,
        public string $description,
        public CategorySlug $slug,
    ) {}
}

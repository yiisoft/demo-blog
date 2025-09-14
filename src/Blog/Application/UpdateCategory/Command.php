<?php

declare(strict_types=1);

namespace App\Blog\Application\UpdateCategory;

use App\Blog\Domain\Category\CategoryId;
use App\Blog\Domain\Category\CategoryName;
use App\Blog\Domain\Category\CategorySlug;

final readonly class Command
{
    public function __construct(
        public CategoryId $id,
        public CategoryName $name,
        public string $description,
        public CategorySlug $slug,
    ) {}
}

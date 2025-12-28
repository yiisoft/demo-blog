<?php

declare(strict_types=1);

namespace App\Presentation\Site\Blog\Manage\Category\Index\DataReader;

use App\Domain\Category\CategoryId;
use App\Domain\Category\CategoryName;
use App\Domain\Category\CategorySlug;

final readonly class Category
{
    public function __construct(
        public CategoryId $id,
        public CategoryName $name,
        public string $description,
        public CategorySlug $slug,
        public int $countPosts,
    ) {}
}

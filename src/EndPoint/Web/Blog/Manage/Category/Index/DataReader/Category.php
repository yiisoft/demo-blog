<?php

declare(strict_types=1);

namespace App\EndPoint\Web\Blog\Manage\Category\Index\DataReader;

use App\Blog\Domain\Category\CategoryId;
use App\Blog\Domain\Category\CategoryName;
use App\Blog\Domain\Category\CategorySlug;

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

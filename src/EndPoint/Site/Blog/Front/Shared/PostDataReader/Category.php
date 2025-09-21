<?php

declare(strict_types=1);

namespace App\EndPoint\Site\Blog\Front\Shared\PostDataReader;

use App\Blog\Domain\Category\CategoryId;
use App\Blog\Domain\Category\CategoryName;
use App\Blog\Domain\Category\CategorySlug;

final readonly class Category
{
    public function __construct(
        public CategoryId $id,
        public CategoryName $name,
        public CategorySlug $slug,
    ) {}
}

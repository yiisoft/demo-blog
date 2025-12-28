<?php

declare(strict_types=1);

namespace App\Shared\Read\Front\RichPostDataReader;

use App\Domain\Category\CategoryId;
use App\Domain\Category\CategoryName;
use App\Domain\Category\CategorySlug;

final readonly class Category
{
    public function __construct(
        public CategoryId $id,
        public CategoryName $name,
        public CategorySlug $slug,
    ) {}
}

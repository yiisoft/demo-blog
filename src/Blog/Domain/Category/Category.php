<?php

declare(strict_types=1);

namespace App\Blog\Domain\Category;

final class Category
{
    public readonly CategoryId $id;
    public private(set) CategoryName $name;
    public private(set) string $description;
    public private(set) CategorySlug $slug;

    public function __construct(
        CategoryId $id,
        CategoryName $name,
        string $description,
        CategorySlug $slug,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->slug = $slug;
    }

    public function changeName(CategoryName $name): void
    {
        $this->name = $name;
    }

    public function changeDescription(string $description): void
    {
        $this->description = $description;
    }

    public function changeSlug(CategorySlug $slug): void
    {
        $this->slug = $slug;
    }
}

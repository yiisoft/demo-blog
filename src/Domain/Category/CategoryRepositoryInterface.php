<?php

declare(strict_types=1);

namespace App\Domain\Category;

interface CategoryRepositoryInterface
{
    public function getOrUserException(CategoryId $id): Category;

    public function tryGetBySlug(CategorySlug $slug): Category|null;

    public function hasBySlug(CategorySlug $slug, CategoryId|null $excludeId = null): bool;

    public function add(Category $category): void;

    public function update(Category $category): void;

    public function delete(CategoryId $id): void;
}

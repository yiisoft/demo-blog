<?php

declare(strict_types=1);

namespace App\Application\Category\CreateCategory;

use App\Application\SlugAlreadyExistException;
use App\Domain\Category\Category;
use App\Domain\Category\CategoryId;
use App\Domain\Category\CategoryRepositoryInterface;
use Ramsey\Uuid\Uuid;

final readonly class Handler
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
    ) {}

    /**
     * @throws SlugAlreadyExistException
     */
    public function handle(Command $command): Result
    {
        if ($this->categoryRepository->hasBySlug($command->slug)) {
            throw SlugAlreadyExistException::fromCategorySlug($command->slug);
        }

        $categoryId = new CategoryId(Uuid::uuid7());

        $category = new Category(
            $categoryId,
            $command->name,
            $command->description,
            $command->slug,
        );

        $this->categoryRepository->add($category);

        return new Result($categoryId);
    }
}

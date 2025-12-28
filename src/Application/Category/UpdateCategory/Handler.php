<?php

declare(strict_types=1);

namespace App\Application\Category\UpdateCategory;

use App\Application\SlugAlreadyExistException;
use App\Domain\Category\CategoryRepositoryInterface;

final readonly class Handler
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
    ) {}

    /**
     * @throws SlugAlreadyExistException
     */
    public function handle(Command $command): void
    {
        if ($this->categoryRepository->hasBySlug($command->slug, $command->id)) {
            throw SlugAlreadyExistException::fromCategorySlug($command->slug);
        }

        $category = $this->categoryRepository->getOrUserException($command->id);

        $category->changeName($command->name);
        $category->changeDescription($command->description);
        $category->changeSlug($command->slug);

        $this->categoryRepository->update($category);
    }
}

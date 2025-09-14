<?php

declare(strict_types=1);

namespace App\Blog\Application\DeleteCategory;

use App\Blog\Domain\Category\CategoryRepositoryInterface;

final readonly class Handler
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
    ) {}

    public function handle(Command $command): void
    {
        $this->categoryRepository->delete($command->id);
    }
}

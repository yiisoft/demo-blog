<?php

declare(strict_types=1);

namespace App\Application\Category\DeleteCategory;

use App\Domain\Category\CategoryRepositoryInterface;

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

<?php

declare(strict_types=1);

namespace App\Blog\Application\DeletePost;

use App\Blog\Domain\Post\PostRepositoryInterface;

final readonly class Handler
{
    public function __construct(
        private PostRepositoryInterface $postRepository,
    ) {}

    public function handle(Command $command): void
    {
        $this->postRepository->delete($command->id);
    }
}

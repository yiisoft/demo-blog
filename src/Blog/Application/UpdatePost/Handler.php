<?php

declare(strict_types=1);

namespace App\Blog\Application\UpdatePost;

use App\Blog\Application\SlugAlreadyExistException;
use App\Blog\Domain\Post\PostRepositoryInterface;
use App\Blog\Domain\Post\PostStatus;

final readonly class Handler
{
    public function __construct(
        private PostRepositoryInterface $postRepository,
    ) {}

    public function handle(Command $command): void
    {
        if ($this->postRepository->hasBySlug($command->slug, $command->id)) {
            throw new SlugAlreadyExistException($command->slug);
        }

        $post = $this->postRepository->getOrUserException($command->id);

        $post->changeTitle($command->title);
        $post->changeBody($command->body);
        $post->changeSlug($command->slug);
        $post->changePublicationDate($command->publicationDate);
        $post->updatedBy($command->updatedBy);

        match ($command->status) {
            PostStatus::Draft => $post->makeDraft(),
            PostStatus::Published => $post->publish(),
            PostStatus::Archived => $post->archive(),
        };

        $this->postRepository->update($post);
    }
}

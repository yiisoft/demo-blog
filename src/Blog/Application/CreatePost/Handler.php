<?php

declare(strict_types=1);

namespace App\Blog\Application\CreatePost;

use App\Blog\Application\SlugAlreadyExistException;
use App\Blog\Domain\Post\Post;
use App\Blog\Domain\Post\PostId;
use App\Blog\Domain\Post\PostRepositoryInterface;
use App\Blog\Domain\Post\PostStatus;
use Ramsey\Uuid\Uuid;

final readonly class Handler
{
    public function __construct(
        private PostRepositoryInterface $postRepository,
    ) {}

    public function handle(Command $command): Result
    {
        if ($this->postRepository->hasBySlug($command->slug)) {
            throw SlugAlreadyExistException::fromPostSlug($command->slug);
        }

        $post = new Post(
            new PostId(Uuid::uuid7()),
            $command->title,
            $command->body,
            $command->slug,
            $command->publicationDate,
            $command->createdBy,
            $command->categoryIds,
        );

        match ($command->status) {
            PostStatus::Draft => null,
            PostStatus::Published => $post->publish(),
            PostStatus::Archived => $post->archive(),
        };

        $this->postRepository->add($post);

        return new Result(
            id: $post->id,
        );
    }
}

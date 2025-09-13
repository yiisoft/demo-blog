<?php

declare(strict_types=1);

namespace App\Blog\Application\CreatePost;

use App\Blog\Domain\Post\Post;
use App\Blog\Domain\Post\PostId;
use App\Blog\Domain\Post\PostRepositoryInterface;
use App\Blog\Domain\Post\PostStatus;
use App\Shared\Uuid\UuidGeneratorInterface;

final readonly class Handler
{
    public function __construct(
        private UuidGeneratorInterface $uuidGenerator,
        private PostRepositoryInterface $postRepository,
    ) {}

    public function handle(Command $command): Result
    {
        $post = new Post(
            new PostId($this->uuidGenerator->uuid7()),
            $command->title,
            $command->content,
            $command->publicationDate,
            $command->createdBy,
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

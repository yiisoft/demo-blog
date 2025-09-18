<?php

declare(strict_types=1);

namespace App\EndPoint\Web\Blog\Manage\Post\Index\DataReader;

use App\Blog\Domain\Post\PostId;
use App\Blog\Domain\Post\PostSlug;
use App\Blog\Domain\Post\PostStatus;
use App\Blog\Domain\Post\PostTitle;
use DateTimeImmutable;

final readonly class Post
{
    public function __construct(
        public PostId $id,
        public PostStatus $status,
        public PostTitle $title,
        public PostSlug $slug,
        public ?DateTimeImmutable $publicationDate,
        public DateTimeImmutable $createdAt,
        public User $createdBy,
        public DateTimeImmutable $updatedAt,
        public User $updatedBy,
        public string $categories,
    ) {}
}

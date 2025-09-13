<?php

declare(strict_types=1);

namespace App\Blog\Application\CreatePost;

use App\Blog\Domain\Post\PostStatus;
use App\Blog\Domain\Post\PostTitle;
use App\User\Domain\UserId;
use DateTimeImmutable;

final readonly class Command
{
    public function __construct(
        public PostTitle $title,
        public string $content,
        public PostStatus $status,
        public DateTimeImmutable|null $publicationDate,
        public UserId $createdBy,
    ) {}
}

<?php

declare(strict_types=1);

namespace App\Blog\Application\UpdatePost;

use App\Blog\Domain\Post\PostId;
use App\Blog\Domain\Post\PostStatus;
use App\Blog\Domain\Post\PostTitle;
use App\User\Domain\UserId;
use DateTimeImmutable;

final readonly class Command
{
    public function __construct(
        public PostId $id,
        public PostTitle $title,
        public string $body,
        public PostStatus $status,
        public ?DateTimeImmutable $publicationDate,
        public UserId $updatedBy,
    ) {}
}

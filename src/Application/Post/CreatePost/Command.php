<?php

declare(strict_types=1);

namespace App\Application\Post\CreatePost;

use App\Domain\Category\CategoryId;
use App\Domain\Post\PostSlug;
use App\Domain\Post\PostStatus;
use App\Domain\Post\PostTitle;
use App\Domain\User\UserId;
use DateTimeImmutable;

final readonly class Command
{
    /**
     * @param list<CategoryId> $categoryIds
     */
    public function __construct(
        public PostTitle $title,
        public string $body,
        public PostSlug $slug,
        public PostStatus $status,
        public DateTimeImmutable|null $publicationDate,
        public UserId $createdBy,
        public array $categoryIds,
    ) {}
}

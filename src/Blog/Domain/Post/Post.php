<?php

declare(strict_types=1);

namespace App\Blog\Domain\Post;

use App\Blog\Domain\Category\CategoryId;
use App\User\Domain\UserId;
use DateTimeImmutable;
use LogicException;

final class Post
{
    public private(set) PostStatus $status = PostStatus::Draft;
    public readonly DateTimeImmutable $createdAt;
    public private(set) DateTimeImmutable $updatedAt;
    public private(set) UserId $updatedBy;

    /**
     * @param list<CategoryId> $categoryIds
     */
    public function __construct(
        public readonly PostId $id,
        public private(set) PostTitle $title,
        public private(set) string $body,
        public private(set) PostSlug $slug,
        public private(set) DateTimeImmutable|null $publicationDate,
        public readonly UserId $createdBy,
        public private(set) array $categoryIds,
    ) {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
        $this->updatedBy = $this->createdBy;
    }

    public function changeTitle(PostTitle $title): void
    {
        $this->title = $title;
    }

    public function changeBody(string $body): void
    {
        $this->body = $body;
    }

    public function changeSlug(PostSlug $slug): void
    {
        $this->slug = $slug;
    }

    public function changePublicationDate(DateTimeImmutable|null $date): void
    {
        if ($date === null && $this->isPublished()) {
            throw new LogicException('Cannot unset publication date of a published post.');
        }

        $this->publicationDate = $date;
    }

    public function publish(): void
    {
        if ($this->publicationDate === null) {
            throw new LogicException('Cannot publish a post without a publication date.');
        }

        $this->status = PostStatus::Published;
    }

    public function archive(): void
    {
        $this->status = PostStatus::Archived;
    }

    public function makeDraft(): void
    {
        $this->status = PostStatus::Draft;
    }

    public function isPublished(): bool
    {
        return $this->status === PostStatus::Published;
    }

    public function isDraft(): bool
    {
        return $this->status === PostStatus::Draft;
    }

    public function isArchived(): bool
    {
        return $this->status === PostStatus::Archived;
    }

    public function updatedBy(UserId $userId): void
    {
        $this->updatedBy = $userId;
        $this->updatedAt = new DateTimeImmutable();
    }

    /**
     * @param list<CategoryId> $categoryIds
     */
    public function changeCategories(array $categoryIds): void
    {
        $this->categoryIds = $categoryIds;
    }
}

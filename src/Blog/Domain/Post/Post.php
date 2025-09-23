<?php

declare(strict_types=1);

namespace App\Blog\Domain\Post;

use App\Blog\Domain\Category\CategoryId;
use App\User\Domain\UserId;
use DateTimeImmutable;
use LogicException;

final class Post
{
    public readonly PostId $id;

    public private(set) PostStatus $status {
        set {
            if ($value === PostStatus::Published && $this->publicationDate === null) {
                throw new LogicException('Cannot publish a post without a publication date.');
            }
            $this->status = $value;
        }
    }

    public private(set) PostTitle $title;
    public private(set) string $body;
    public private(set) PostSlug $slug;

    public private(set) DateTimeImmutable|null $publicationDate {
        set {
            if ($value === null && $this->isPublished()) {
                throw new LogicException('Cannot unset publication date of a published post.');
            }
            $this->publicationDate = $value;
        }
    }

    public readonly DateTimeImmutable $createdAt;
    public readonly UserId $createdBy;
    public private(set) DateTimeImmutable $updatedAt;
    public private(set) UserId $updatedBy;
    /**
     * @var list<CategoryId>
     */
    public private(set) array $categoryIds;

    /**
     * @param list<CategoryId> $categoryIds
     */
    public function __construct(
        PostId $id,
        PostTitle $title,
        string $body,
        PostSlug $slug,
        DateTimeImmutable|null $publicationDate,
        UserId $createdBy,
        array $categoryIds,
    ) {
        $this->id = $id;
        $this->status = PostStatus::Draft;
        $this->title = $title;
        $this->body = $body;
        $this->slug = $slug;
        $this->publicationDate = $publicationDate;
        $this->createdAt = new DateTimeImmutable();
        $this->createdBy = $createdBy;
        $this->updatedAt = new DateTimeImmutable();
        $this->updatedBy = $createdBy;
        $this->categoryIds = $categoryIds;
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
        $this->publicationDate = $date;
    }

    public function publish(): void
    {
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

<?php

declare(strict_types=1);

namespace App\Blog\Domain\Post;

use App\User\Domain\UserId;
use DateTimeImmutable;
use LogicException;

final class Post
{
    public readonly PostId $id;
    public private(set) PostStatus $status;
    public private(set) PostTitle $title;
    public private(set) string $body;
    public private(set) DateTimeImmutable|null $publicationDate;
    public readonly DateTimeImmutable $createdAt;
    public readonly UserId $createdBy;
    public private(set) DateTimeImmutable $updatedAt;
    public private(set) UserId $updatedBy;

    public function __construct(
        PostId $id,
        PostTitle $title,
        string $body,
        DateTimeImmutable|null $publicationDate,
        UserId $createdBy,
    ) {
        $this->id = $id;
        $this->status = PostStatus::Draft;
        $this->title = $title;
        $this->body = $body;
        $this->publicationDate = $publicationDate;
        $this->createdAt = new DateTimeImmutable();
        $this->createdBy = $createdBy;
        $this->updatedAt = new DateTimeImmutable();
        $this->updatedBy = $createdBy;
    }

    public function changeTitle(PostTitle $title): void
    {
        $this->title = $title;
    }

    public function changeContent(string $content): void
    {
        $this->body = $content;
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
}

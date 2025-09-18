<?php

declare(strict_types=1);

namespace App\EndPoint\Web\Blog\Front\Shared\PostDataReader;

use App\Blog\Domain\Post\PostId;
use App\Blog\Domain\Post\PostSlug;
use App\Blog\Domain\Post\PostTitle;
use DateTimeImmutable;
use Yiisoft\Strings\StringHelper;

final readonly class Post
{
    public string $description;

    public function __construct(
        public PostId $id,
        public PostTitle $title,
        public PostSlug $slug,
        public string $body,
        public DateTimeImmutable $publicationDate,
        /** @var list<Category> */
        public array $categories,
    ) {
        $this->description = StringHelper::truncateBegin($body, 500);
    }
}

<?php

declare(strict_types=1);

namespace App\Blog\Application\CreatePost;

use App\Blog\Domain\Post\PostId;

final readonly class Result
{
    public function __construct(
        public PostId $id,
    ) {}
}

<?php

declare(strict_types=1);

namespace App\Application\Post\CreatePost;

use App\Domain\Post\PostId;

final readonly class Result
{
    public function __construct(
        public PostId $id,
    ) {}
}

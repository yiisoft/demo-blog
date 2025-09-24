<?php

declare(strict_types=1);

namespace App\Blog\Application\DeletePost;

use App\Blog\Domain\Post\PostId;

final readonly class Command
{
    public function __construct(
        public PostId $id,
    ) {}
}

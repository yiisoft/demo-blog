<?php

declare(strict_types=1);

namespace App\Application\Post\DeletePost;

use App\Domain\Post\PostId;

final readonly class Command
{
    public function __construct(
        public PostId $id,
    ) {}
}

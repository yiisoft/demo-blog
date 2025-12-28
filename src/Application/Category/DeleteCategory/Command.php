<?php

declare(strict_types=1);

namespace App\Application\Category\DeleteCategory;

use App\Domain\Category\CategoryId;

final readonly class Command
{
    public function __construct(
        public CategoryId $id,
    ) {}
}

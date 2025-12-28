<?php

declare(strict_types=1);

namespace App\Presentation\Site\Shared\Layout\ContentNotices;

final readonly class Notice
{
    public function __construct(
        public Type $type,
        public string $message,
    ) {}
}

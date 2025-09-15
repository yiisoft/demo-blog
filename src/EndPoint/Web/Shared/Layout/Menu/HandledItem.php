<?php

declare(strict_types=1);

namespace App\EndPoint\Web\Shared\Layout\Menu;

final readonly class HandledItem
{
    public function __construct(
        public string $label,
        public string|null $url,
        public bool $active,
    ) {}
}

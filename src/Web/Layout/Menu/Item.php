<?php

declare(strict_types=1);

namespace App\Web\Layout\Menu;

use Closure;
use Stringable;

final readonly class Item
{
    /**
     * @param array<string, null|Stringable|scalar> $urlParameters
     */
    public function __construct(
        public string $label,
        public string|null $urlName = null,
        public array $urlParameters = [],
        public string|null $customUrl = null,
        public bool $onlyAuthenticated = false,
        public ?Closure $activeCallback = null,
    ) {}
}

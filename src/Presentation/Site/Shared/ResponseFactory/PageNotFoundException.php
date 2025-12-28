<?php

declare(strict_types=1);

namespace App\Presentation\Site\Shared\ResponseFactory;

use Exception;

final class PageNotFoundException extends Exception
{
    public function __construct(
        public readonly string $title = 'Page not found',
        public readonly string $description = '',
    ) {
        parent::__construct($title . ' / ' . $description);
    }
}

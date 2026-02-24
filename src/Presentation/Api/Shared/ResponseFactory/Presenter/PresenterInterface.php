<?php

declare(strict_types=1);

namespace App\Presentation\Api\Shared\ResponseFactory\Presenter;

/**
 * @template T
 */
interface PresenterInterface
{
    /**
     * @param T $value
     */
    public function present(mixed $value): mixed;
}

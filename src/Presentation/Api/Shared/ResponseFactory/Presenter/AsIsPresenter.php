<?php

declare(strict_types=1);

namespace App\Presentation\Api\Shared\ResponseFactory\Presenter;

/**
 * @implements PresenterInterface<mixed>
 */
final readonly class AsIsPresenter implements PresenterInterface
{
    public function present(mixed $value): mixed
    {
        return $value;
    }
}

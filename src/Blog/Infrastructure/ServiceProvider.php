<?php

declare(strict_types=1);

namespace App\Blog\Infrastructure;

use App\Blog\Domain\Post\PostRepositoryInterface;
use Yiisoft\Di\ServiceProviderInterface;

final readonly class ServiceProvider implements ServiceProviderInterface
{
    public function getDefinitions(): array
    {
        return [
            PostRepositoryInterface::class => DbPostRepository::class,
        ];
    }

    public function getExtensions(): array
    {
        return [];
    }
}

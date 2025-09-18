<?php

declare(strict_types=1);

namespace App\Shared;

use App\Blog\Domain\Category\CategorySlug;
use App\Blog\Domain\Post\PostSlug;
use Yiisoft\Router\UrlGeneratorInterface;

/**
 * @psalm-import-type UrlArgumentsType from UrlGeneratorInterface
 */
final readonly class UrlGenerator
{
    public function __construct(
        private UrlGeneratorInterface $generator,
    ) {}

    public function home(): string
    {
        return $this->generate('home');
    }

    public function login(): string
    {
        return $this->generate('login');
    }

    public function logout(): string
    {
        return $this->generate('logout');
    }

    public function profileChangePassword(): string
    {
        return $this->generate('profile/change-password');
    }

    public function profileUpdate(): string
    {
        return $this->generate('profile/update');
    }

    public function blog(int|string $page = 1): string
    {
        return $this->generate('blog/post/index', $page === 1 ? [] : ['page' => $page]);
    }

    public function category(CategorySlug $slug, int|string $page = 1): string
    {
        return $this->generate('blog/category/index', [
            'slug' => $slug,
            ...($page === 1 ? [] : ['page' => $page]),
        ]);
    }

    public function post(PostSlug $slug): string
    {
        return $this->generate('blog/post/view', ['slug' => $slug]);
    }

    /**
     * @param UrlArgumentsType $arguments
     */
    public function generate(string $name, array $arguments = [], array $queryParameters = []): string
    {
        return $this->generator->generate($name, $arguments, $queryParameters);
    }
}

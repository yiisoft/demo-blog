<?php

declare(strict_types=1);

namespace App\Shared;

use App\Blog\Domain\Category\CategoryId;
use App\Blog\Domain\Category\CategorySlug;
use App\Blog\Domain\Post\PostId;
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

    public function category(CategorySlug $slug, int|string $page = 1, bool $absolute = false): string
    {
        $arguments = [
            'slug' => $slug,
            ...($page === 1 ? [] : ['page' => $page]),
        ];
        return $absolute
            ? $this->generateAbsolute('blog/category/index', $arguments)
            : $this->generate('blog/category/index', $arguments);
    }

    public function categoryUpdate(CategoryId $id): string
    {
        return $this->generate('blog/manage/category/update', ['id' => $id]);
    }

    public function post(PostSlug $slug, bool $absolute = false): string
    {
        return $absolute
            ? $this->generateAbsolute('blog/post/view', ['slug' => $slug])
            : $this->generate('blog/post/view', ['slug' => $slug]);
    }

    public function postUpdate(PostId $id): string
    {
        return $this->generate('blog/manage/post/update', ['id' => $id]);
    }

    /**
     * @param UrlArgumentsType $arguments
     */
    public function generate(string $name, array $arguments = [], array $queryParameters = []): string
    {
        return $this->generator->generate($name, $arguments, $queryParameters);
    }

    /**
     * @param UrlArgumentsType $arguments
     */
    public function generateAbsolute(string $name, array $arguments = [], array $queryParameters = []): string
    {
        return $this->generator->generateAbsolute($name, $arguments, $queryParameters);
    }
}

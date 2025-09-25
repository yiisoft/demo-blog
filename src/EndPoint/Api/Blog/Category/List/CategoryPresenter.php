<?php

declare(strict_types=1);

namespace App\EndPoint\Api\Blog\Category\List;

use App\Blog\Read\Front\RichCategories\Category;
use App\EndPoint\Api\Shared\ResponseFactory\Presenter\PresenterInterface;
use App\Shared\Infrastructure\UrlGenerator;
use Yiisoft\DataResponse\DataResponse;

/**
 * @implements PresenterInterface<Category>
 */
final readonly class CategoryPresenter implements PresenterInterface
{
    public function __construct(
        private UrlGenerator $urlGenerator,
    ) {}

    public function present(mixed $value, DataResponse $response): DataResponse
    {
        return $response->withData([
            'id' => $value->id->toString(),
            'name' => $value->name->toString(),
            'url' => $this->urlGenerator->category($value->slug, absolute: true),
            'count_posts' => $value->countPosts,
        ]);
    }
}

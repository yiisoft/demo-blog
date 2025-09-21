<?php

declare(strict_types=1);

namespace App\EndPoint\Api\Blog\Post\List;

use App\Blog\Read\RichPostDataReader\Post;
use App\EndPoint\Api\Shared\ResponseFactory\Presenter\PresenterInterface;
use App\Shared\UrlGenerator;
use Yiisoft\DataResponse\DataResponse;

/**
 * @implements PresenterInterface<Post>
 */
final readonly class PostPresenter implements PresenterInterface
{
    public function __construct(
        private UrlGenerator $urlGenerator,
    ) {}

    public function present(mixed $value, DataResponse $response): DataResponse
    {
        return $response->withData([
            'id' => $value->id->toString(),
            'title' => $value->title->toString(),
            'url' => $this->urlGenerator->post($value->slug, absolute: true),
            'body' => $value->body,
            'publicationDate' => $value->publicationDate->format('Y-m-d'),
            'categories' => array_map(
                static fn($category) => $category->id->toString(),
                $value->categories,
            ),
        ]);
    }
}

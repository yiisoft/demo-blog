<?php

declare(strict_types=1);

namespace App\EndPoint\Api\Blog\Category\List;

use App\Blog\Read\RichCategories\CategoryReader;
use App\EndPoint\Api\Shared\ResponseFactory\Presenter\CollectionPresenter;
use App\EndPoint\Api\Shared\ResponseFactory\ResponseFactory;
use App\Shared\UrlGenerator;
use Psr\Http\Message\ResponseInterface;

final readonly class Action
{
    public function __construct(
        private ResponseFactory $responseFactory,
        private CategoryReader $categoryReader,
        private UrlGenerator $urlGenerator,
    ) {}

    public function __invoke(): ResponseInterface
    {
        return $this->responseFactory->success(
            $this->categoryReader->find(),
            new CollectionPresenter(
                new CategoryPresenter($this->urlGenerator),
            ),
        );
    }
}

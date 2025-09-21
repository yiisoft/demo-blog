<?php

declare(strict_types=1);

namespace App\EndPoint\Api\Blog\Post\List;

use App\Blog\Domain\Category\CategoryId;
use App\Blog\Read\RichPostDataReader\PostDataReaderFactory;
use App\EndPoint\Api\Shared\ResponseFactory\Presenter\OffsetPaginatorPresenter;
use App\EndPoint\Api\Shared\ResponseFactory\ResponseFactory;
use App\Shared\UrlGenerator;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Data\Paginator\OffsetPaginator;
use Yiisoft\Input\Http\Attribute\Parameter\Query;

final readonly class Action
{
    public function __construct(
        private ResponseFactory $responseFactory,
        private PostDataReaderFactory $postDataReaderFactory,
        private UrlGenerator $urlGenerator,
    ) {}

    public function __invoke(
        #[Query('page')]
        int $page = 1,
        #[Query('categoryId')]
        CategoryId|null $categoryId = null,
    ): ResponseInterface {
        $paginator = new OffsetPaginator(
            $this->postDataReaderFactory->create($categoryId),
        );

        if ($page < 1) {
            return $this->responseFactory->fail('Invalid page number.');
        }
        if ($page !== 1 && $page > $paginator->getTotalPages()) {
            return $this->responseFactory->fail('Page not found.');
        }
        $paginator = $paginator->withCurrentPage($page);

        return $this->responseFactory->success(
            $paginator,
            new OffsetPaginatorPresenter(
                new PostPresenter($this->urlGenerator),
            ),
        );
    }
}

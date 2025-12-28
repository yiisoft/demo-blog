<?php

declare(strict_types=1);

namespace App\Presentation\Api\Blog\Post\List;

use App\Presentation\Api\Shared\ResponseFactory\Presenter\OffsetPaginatorPresenter;
use App\Presentation\Api\Shared\ResponseFactory\ResponseFactory;
use App\Shared\Read\Front\RichPostDataReader\PostDataReaderFactory;
use App\Shared\UrlGenerator;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Data\Paginator\OffsetPaginator;
use Yiisoft\Http\Status;

final readonly class Action
{
    public function __construct(
        private ResponseFactory $responseFactory,
        private PostDataReaderFactory $postDataReaderFactory,
        private UrlGenerator $urlGenerator,
    ) {}

    public function __invoke(Input $input): ResponseInterface
    {
        $paginator = new OffsetPaginator(
            $this->postDataReaderFactory->create($input->categoryId),
        );

        if ($input->page !== 1 && $input->page > $paginator->getTotalPages()) {
            return $this->responseFactory->fail('Page not found.', httpCode: Status::NOT_FOUND);
        }
        $paginator = $paginator->withCurrentPage($input->page);

        return $this->responseFactory->success(
            $paginator,
            new OffsetPaginatorPresenter(
                new PostPresenter($this->urlGenerator),
            ),
        );
    }
}

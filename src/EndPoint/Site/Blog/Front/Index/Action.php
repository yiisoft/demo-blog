<?php

declare(strict_types=1);

namespace App\EndPoint\Site\Blog\Front\Index;

use App\Blog\Read\Front\RichCategories\CategoryReader;
use App\Blog\Read\Front\RichPostDataReader\PostDataReaderFactory;
use App\EndPoint\Site\Shared\ResponseFactory\ResponseFactory;
use App\Shared\Infrastructure\UrlGenerator;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Data\Paginator\OffsetPaginator;
use Yiisoft\Router\HydratorAttribute\RouteArgument;

final readonly class Action
{
    public function __construct(
        private PostDataReaderFactory $postDataReaderFactory,
        private ResponseFactory $responseFactory,
        private UrlGenerator $urlGenerator,
        private CategoryReader $categoryReader,
    ) {}

    public function __invoke(
        #[RouteArgument('page')]
        int $page = 1,
    ): ResponseInterface {
        $paginator = new OffsetPaginator($this->postDataReaderFactory->create());

        if ($page !== 1
            && ($page < 1 || $page > $paginator->getTotalPages())
        ) {
            return $this->responseFactory->temporarilyRedirect(
                $this->urlGenerator->blog(),
            );
        }

        $paginator = $paginator->withCurrentPage($page);

        return $this->responseFactory->render(
            __DIR__ . '/template.php',
            [
                'paginator' => $paginator,
                'categories' => $this->categoryReader->find(),
            ],
        );
    }
}

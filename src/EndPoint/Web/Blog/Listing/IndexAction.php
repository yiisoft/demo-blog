<?php

declare(strict_types=1);

namespace App\EndPoint\Web\Blog\Listing;

use App\EndPoint\Web\Blog\Listing\PostDataReader\PostDataReader;
use App\Shared\UrlGenerator;
use App\Web\ResponseFactory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Data\Paginator\OffsetPaginator;
use Yiisoft\Router\HydratorAttribute\RouteArgument;

final readonly class IndexAction
{
    public function __construct(
        private PostDataReader $dataReader,
        private ResponseFactory $responseFactory,
        private UrlGenerator $urlGenerator,
    ) {}

    public function __invoke(
        #[RouteArgument('page')]
        int $page = 1,
    ): ResponseInterface {
        $paginator = new OffsetPaginator($this->dataReader);

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
            ],
        );
    }
}

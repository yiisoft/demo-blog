<?php

declare(strict_types=1);

namespace App\EndPoint\Site\Blog\Front\Category;

use App\Blog\Domain\Category\CategoryRepositoryInterface;
use App\Blog\Domain\Category\CategorySlug;
use App\Blog\Read\Front\RichCategories\CategoryReader;
use App\Blog\Read\Front\RichPostDataReader\PostDataReaderFactory;
use App\Shared\UrlGenerator;
use App\EndPoint\Site\Shared\ResponseFactory\ResponseFactory;
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
        private CategoryRepositoryInterface $categoryRepository,
    ) {}

    public function __invoke(
        #[RouteArgument('slug')]
        string $rawSlug,
        #[RouteArgument('page')]
        int $page = 1,
    ): ResponseInterface {
        $slug = CategorySlug::tryFromString($rawSlug);
        if ($slug === null) {
            return $this->responseFactory->notFound();
        }

        $category = $this->categoryRepository->tryGetBySlug($slug);
        if ($category === null) {
            return $this->responseFactory->notFound();
        }

        $paginator = new OffsetPaginator(
            $this->postDataReaderFactory->create($category->id),
        );

        if ($page !== 1
            && ($page < 1 || $page > $paginator->getTotalPages())
        ) {
            return $this->responseFactory->temporarilyRedirect(
                $this->urlGenerator->category($category->slug),
            );
        }

        $paginator = $paginator->withCurrentPage($page);

        return $this->responseFactory->render(
            __DIR__ . '/template.php',
            [
                'category' => $category,
                'paginator' => $paginator,
                'categories' => $this->categoryReader->find(),
            ],
        );
    }
}

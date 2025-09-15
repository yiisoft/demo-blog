<?php

declare(strict_types=1);

namespace App\EndPoint\Web\Blog\Manage\Category\Delete;

use App\Blog\Application\DeleteCategory\Command;
use App\Blog\Application\DeleteCategory\Handler;
use App\Blog\Domain\Category\CategoryId;
use App\Blog\Domain\Category\CategoryRepositoryInterface;
use App\Shared\UrlGenerator;
use App\EndPoint\Web\Shared\Layout\ContentNotices\ContentNotices;
use App\EndPoint\Web\Shared\ResponseFactory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Http\Method;
use Yiisoft\Router\HydratorAttribute\RouteArgument;

use function sprintf;

final readonly class Action
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
        private Handler $handler,
        private ContentNotices $contentNotices,
        private ResponseFactory $responseFactory,
        private UrlGenerator $urlGenerator,
    ) {}

    public function __invoke(
        #[RouteArgument('id')]
        CategoryId $categoryId,
        ServerRequestInterface $request,
    ): ResponseInterface {
        $category = $this->categoryRepository->getOrUserException($categoryId);

        if ($request->getMethod() !== Method::POST) {
            return $this->responseFactory->render(
                __DIR__ . '/template.php',
                ['category' => $category],
            );
        }

        $this->handler->handle(new Command($categoryId));

        $this->contentNotices->success(
            sprintf(
                'Category "%s" with ID "%s" is deleted.',
                $category->name,
                $category->id,
            ),
        );
        return $this->responseFactory->temporarilyRedirect($this->urlGenerator->generate('blog/manage/category/index'));
    }
}

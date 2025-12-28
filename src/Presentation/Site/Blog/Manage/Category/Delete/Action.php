<?php

declare(strict_types=1);

namespace App\Presentation\Site\Blog\Manage\Category\Delete;

use App\Application\Category\DeleteCategory\Command;
use App\Application\Category\DeleteCategory\Handler;
use App\Domain\Category\CategoryId;
use App\Domain\Category\CategoryRepositoryInterface;
use App\Presentation\Site\Shared\Layout\ContentNotices\ContentNotices;
use App\Presentation\Site\Shared\ResponseFactory\ResponseFactory;
use App\Presentation\Site\Shared\ResponseFactory\ValidateOrNotFound\ValidateOrNotFound;
use App\Shared\UrlGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Http\Method;
use Yiisoft\Router\HydratorAttribute\RouteArgument;
use Yiisoft\Validator\Rule\Uuid;

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
        #[ValidateOrNotFound(new Uuid())]
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

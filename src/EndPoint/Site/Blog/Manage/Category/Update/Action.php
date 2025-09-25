<?php

declare(strict_types=1);

namespace App\EndPoint\Site\Blog\Manage\Category\Update;

use App\Blog\Application\SlugAlreadyExistException;
use App\Blog\Application\UpdateCategory\Handler;
use App\Blog\Domain\Category\CategoryId;
use App\Blog\Domain\Category\CategoryRepositoryInterface;
use App\EndPoint\Site\Shared\Layout\ContentNotices\ContentNotices;
use App\EndPoint\Site\Shared\ResponseFactory\ResponseFactory;
use App\EndPoint\Site\Shared\ResponseFactory\ValidateOrNotFound\ValidateOrNotFound;
use App\Shared\Infrastructure\UrlGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Router\HydratorAttribute\RouteArgument;
use Yiisoft\Strings\Inflector;
use Yiisoft\Validator\Rule\Uuid;

use function sprintf;

final readonly class Action
{
    public function __construct(
        private FormHydrator $formHydrator,
        private Handler $handler,
        private CategoryRepositoryInterface $categoryRepository,
        private ContentNotices $contentNotices,
        private ResponseFactory $responseFactory,
        private UrlGenerator $urlGenerator,
        private Inflector $inflector,
    ) {}

    public function __invoke(
        #[RouteArgument('id')]
        #[ValidateOrNotFound(new Uuid())]
        CategoryId $categoryId,
        ServerRequestInterface $request,
    ): ResponseInterface {
        $category = $this->categoryRepository->getOrUserException($categoryId);
        $form = new Form($category);

        if (!$this->formHydrator->populateFromPostAndValidate($form, $request)) {
            return $this->renderForm($form);
        }

        $command = $form->createCommand($this->inflector);

        try {
            $this->handler->handle($command);
        } catch (SlugAlreadyExistException $exception) {
            $form->getValidationResult()->addError($exception->getMessage(), valuePath: ['slug']);
            return $this->renderForm($form);
        }

        $this->contentNotices->success(
            sprintf(
                'Category "%s" is updated.',
                $form->name,
            ),
        );
        return $this->responseFactory->temporarilyRedirect($this->urlGenerator->generate('blog/manage/category/index'));
    }

    private function renderForm(Form $form): ResponseInterface
    {
        return $this->responseFactory->render(
            __DIR__ . '/template.php',
            ['form' => $form],
        );
    }
}

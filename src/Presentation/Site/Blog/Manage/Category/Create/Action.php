<?php

declare(strict_types=1);

namespace App\Presentation\Site\Blog\Manage\Category\Create;

use App\Application\Category\CreateCategory\Handler;
use App\Application\SlugAlreadyExistException;
use App\Presentation\Site\Shared\Layout\ContentNotices\ContentNotices;
use App\Presentation\Site\Shared\ResponseFactory\ResponseFactory;
use App\Shared\UrlGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Strings\Inflector;

use function sprintf;

final readonly class Action
{
    public function __construct(
        private FormHydrator $formHydrator,
        private Handler $handler,
        private ContentNotices $contentNotices,
        private ResponseFactory $responseFactory,
        private UrlGenerator $urlGenerator,
        private Inflector $inflector,
    ) {}

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $form = new Form();

        if (!$this->formHydrator->populateFromPostAndValidate($form, $request)) {
            return $this->renderForm($form);
        }

        $command = $form->createCommand($this->inflector);

        try {
            $result = $this->handler->handle($command);
        } catch (SlugAlreadyExistException $exception) {
            $form->getValidationResult()->addError($exception->getMessage(), valuePath: ['slug']);
            return $this->renderForm($form);
        }

        $this->contentNotices->success(
            sprintf(
                'Category "%s" with ID "%s" is created.',
                $form->name,
                $result->id,
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

<?php

declare(strict_types=1);

namespace App\Presentation\Site\Blog\Manage\Post\Create;

use App\Application\Post\CreatePost\Handler;
use App\Application\SlugAlreadyExistException;
use App\Presentation\Site\Shared\Identity\AuthenticatedUserProvider;
use App\Presentation\Site\Shared\Layout\ContentNotices\ContentNotices;
use App\Presentation\Site\Shared\ResponseFactory\ResponseFactory;
use App\Shared\Read\CategoriesList\CategoriesListReader;
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
        private AuthenticatedUserProvider $authenticatedUserProvider,
        private Inflector $inflector,
        private CategoriesListReader $categoriesListReader,
    ) {}

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $form = new Form(
            $this->categoriesListReader->all(),
        );

        if (!$this->formHydrator->populateFromPostAndValidate($form, $request)) {
            return $this->renderForm($form);
        }

        $command = $form->createCommand(
            $this->authenticatedUserProvider->getId(),
            $this->inflector,
        );

        try {
            $result = $this->handler->handle($command);
        } catch (SlugAlreadyExistException $exception) {
            $form->getValidationResult()->addError($exception->getMessage(), valuePath: ['slug']);
            return $this->renderForm($form);
        }

        $this->contentNotices->success(
            sprintf(
                'Post "%s" with ID "%s" is created.',
                $form->title,
                $result->id,
            ),
        );
        return $this->responseFactory->temporarilyRedirect($this->urlGenerator->generate('blog/manage/post/index'));
    }

    private function renderForm(Form $form): ResponseInterface
    {
        return $this->responseFactory->render(
            __DIR__ . '/template.php',
            ['form' => $form],
        );
    }
}

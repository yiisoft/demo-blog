<?php

declare(strict_types=1);

namespace App\EndPoint\Web\Blog\Manage\Post\Update;

use App\Blog\Application\SlugAlreadyExistException;
use App\Blog\Application\UpdatePost\Handler;
use App\Blog\Domain\Post\PostId;
use App\Blog\Domain\Post\PostRepositoryInterface;
use App\Blog\Read\CategoriesList\CategoriesListReader;
use App\Shared\UrlGenerator;
use App\EndPoint\Web\Shared\Identity\AuthenticatedUserProvider;
use App\EndPoint\Web\Shared\Layout\ContentNotices\ContentNotices;
use App\EndPoint\Web\Shared\ResponseFactory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Router\HydratorAttribute\RouteArgument;
use Yiisoft\Strings\Inflector;

use function sprintf;

final readonly class Action
{
    public function __construct(
        private FormHydrator $formHydrator,
        private Handler $handler,
        private PostRepositoryInterface $postRepository,
        private ContentNotices $contentNotices,
        private ResponseFactory $responseFactory,
        private UrlGenerator $urlGenerator,
        private AuthenticatedUserProvider $authenticatedUserProvider,
        private Inflector $inflector,
        private CategoriesListReader $categoriesListReader,
    ) {}

    public function __invoke(
        #[RouteArgument('id')]
        PostId $postId,
        ServerRequestInterface $request,
    ): ResponseInterface {
        $post = $this->postRepository->getOrUserException($postId);
        $form = new Form(
            $post,
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
            $this->handler->handle($command);
        } catch (SlugAlreadyExistException $exception) {
            $form->getValidationResult()->addError($exception->getMessage(), valuePath: ['slug']);
            return $this->renderForm($form);
        }

        $this->contentNotices->success(
            sprintf(
                'Post "%s" is updated.',
                $form->title,
            ),
        );
        return $this->responseFactory->temporarilyRedirect(
            $this->urlGenerator->postUpdate($post->id),
        );
    }

    private function renderForm(Form $form): ResponseInterface
    {
        return $this->responseFactory->render(
            __DIR__ . '/template.php',
            ['form' => $form],
        );
    }
}

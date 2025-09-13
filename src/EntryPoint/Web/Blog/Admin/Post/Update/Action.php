<?php

declare(strict_types=1);

namespace App\EntryPoint\Web\Blog\Admin\Post\Update;

use App\Blog\Application\UpdatePost\Handler;
use App\Blog\Domain\Post\PostId;
use App\Blog\Domain\Post\PostRepositoryInterface;
use App\Shared\UrlGenerator;
use App\Web\Identity\AuthenticatedUserProvider;
use App\Web\Layout\ContentNotices\ContentNotices;
use App\Web\ResponseFactory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Router\HydratorAttribute\RouteArgument;

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
    ) {}

    public function __invoke(
        #[RouteArgument('id')]
        PostId $postId,
        ServerRequestInterface $request,
    ): ResponseInterface {
        $post = $this->postRepository->getOrUserException($postId);
        $form = new Form($post);

        if (!$this->formHydrator->populateFromPostAndValidate($form, $request)) {
            return $this->responseFactory->render(
                __DIR__ . '/template.php',
                ['form' => $form],
            );
        }

        $updatedBy = $this->authenticatedUserProvider->getId();
        $this->handler->handle($form->createCommand($updatedBy));

        $this->contentNotices->success(
            sprintf(
                'Post "%s" is updated.',
                $form->title,
            ),
        );
        return $this->responseFactory->temporarilyRedirect($this->urlGenerator->generate('blog/admin/post/index'));
    }
}

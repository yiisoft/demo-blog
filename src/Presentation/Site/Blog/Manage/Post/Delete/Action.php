<?php

declare(strict_types=1);

namespace App\Presentation\Site\Blog\Manage\Post\Delete;

use App\Application\Post\DeletePost\Command;
use App\Application\Post\DeletePost\Handler;
use App\Domain\Post\PostId;
use App\Domain\Post\PostRepositoryInterface;
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
        private PostRepositoryInterface $postRepository,
        private Handler $handler,
        private ContentNotices $contentNotices,
        private ResponseFactory $responseFactory,
        private UrlGenerator $urlGenerator,
    ) {}

    public function __invoke(
        #[RouteArgument('id')]
        #[ValidateOrNotFound(new Uuid())]
        PostId $postId,
        ServerRequestInterface $request,
    ): ResponseInterface {
        $post = $this->postRepository->getOrUserException($postId);

        if ($request->getMethod() !== Method::POST) {
            return $this->responseFactory->render(
                __DIR__ . '/template.php',
                ['post' => $post],
            );
        }

        $this->handler->handle(new Command($postId));

        $this->contentNotices->success(
            sprintf(
                'Post "%s" with ID "%s" is deleted.',
                $post->title,
                $post->id,
            ),
        );
        return $this->responseFactory->temporarilyRedirect($this->urlGenerator->generate('blog/manage/post/index'));
    }
}

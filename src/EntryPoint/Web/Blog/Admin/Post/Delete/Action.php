<?php

declare(strict_types=1);

namespace App\EntryPoint\Web\Blog\Admin\Post\Delete;

use App\Blog\Application\DeletePost\Command;
use App\Blog\Application\DeletePost\Handler;
use App\Blog\Domain\Post\PostId;
use App\Blog\Domain\Post\PostRepositoryInterface;
use App\Shared\UrlGenerator;
use App\Web\Layout\ContentNotices\ContentNotices;
use App\Web\ResponseFactory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Http\Method;
use Yiisoft\Router\HydratorAttribute\RouteArgument;

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
        return $this->responseFactory->temporarilyRedirect($this->urlGenerator->generate('blog/admin/post/index'));
    }
}

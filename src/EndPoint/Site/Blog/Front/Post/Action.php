<?php

declare(strict_types=1);

namespace App\EndPoint\Site\Blog\Front\Post;

use App\Blog\Domain\Post\PostSlug;
use App\EndPoint\Site\Blog\Front\Shared\PostDataReader\Post;
use App\EndPoint\Site\Blog\Front\Shared\PostDataReader\PostDataReaderFactory;
use App\EndPoint\Site\Shared\ResponseFactory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Data\Reader\Filter\Equals;
use Yiisoft\Router\HydratorAttribute\RouteArgument;

final readonly class Action
{
    public function __construct(
        private PostDataReaderFactory $postDataReaderFactory,
        private ResponseFactory $responseFactory,
    ) {}

    public function __invoke(
        #[RouteArgument('slug')]
        string $rawSlug,
    ): ResponseInterface {
        $slug = PostSlug::tryFromString($rawSlug);
        if ($slug === null) {
            return $this->responseFactory->notFound();
        }

        /** @var Post|null $post */
        $post = $this->postDataReaderFactory
            ->create()
            ->withFilter(new Equals('slug', $slug->toString()))
            ->readOne();

        if ($post === null) {
            return $this->responseFactory->notFound();
        }

        return $this->responseFactory->render(
            __DIR__ . '/template.php',
            [
                'post' => $post,
            ],
        );
    }
}

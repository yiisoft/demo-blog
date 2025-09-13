<?php

declare(strict_types=1);

namespace App\EntryPoint\Web\Blog\Admin\Post\Create;

use App\Blog\Application\CreatePost\Handler;
use App\Shared\UrlGenerator;
use App\Web\Identity\AuthenticatedUserProvider;
use App\Web\Layout\ContentNotices\ContentNotices;
use App\Web\ResponseFactory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;

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
    ) {}

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $form = new Form();

        if (!$this->formHydrator->populateFromPostAndValidate($form, $request)) {
            return $this->responseFactory->render(
                __DIR__ . '/template.php',
                ['form' => $form],
            );
        }

        $currentUserId = $this->authenticatedUserProvider->getId();
        $result = $this->handler->handle($form->createCommand($currentUserId));

        $this->contentNotices->success(
            sprintf(
                'Post "%s" with ID "%s" is created.',
                $form->title,
                $result->id,
            ),
        );
        return $this->responseFactory->temporarilyRedirect($this->urlGenerator->generate('blog/admin/post/index'));
    }
}

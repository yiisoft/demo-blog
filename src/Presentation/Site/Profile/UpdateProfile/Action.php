<?php

declare(strict_types=1);

namespace App\Presentation\Site\Profile\UpdateProfile;

use App\Application\User\UpdateProfile\Handler;
use App\Domain\User\UserRepositoryInterface;
use App\Presentation\Site\Shared\Identity\AuthenticatedUserProvider;
use App\Presentation\Site\Shared\Layout\ContentNotices\ContentNotices;
use App\Presentation\Site\Shared\ResponseFactory\ResponseFactory;
use App\Shared\UrlGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;

final readonly class Action
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private AuthenticatedUserProvider $authenticatedUserProvider,
        private ResponseFactory $responseFactory,
        private FormHydrator $formHydrator,
        private Handler $handler,
        private UrlGenerator $urlGenerator,
        private ContentNotices $contentNotices,
    ) {}

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $user = $this->userRepository->getOrUserException(
            $this->authenticatedUserProvider->getId(),
        );

        $form = new Form($user);
        if (!$this->formHydrator->populateFromPostAndValidate($form, $request)) {
            return $this->responseFactory->render(
                __DIR__ . '/template.php',
                ['form' => $form],
            );
        }

        $this->handler->handle(
            $form->createCommand(),
        );

        $this->contentNotices->success('Profile updated successfully.');
        return $this->responseFactory->temporarilyRedirect(
            $this->urlGenerator->profileUpdate(),
        );
    }
}

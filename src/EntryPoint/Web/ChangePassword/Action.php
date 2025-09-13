<?php

declare(strict_types=1);

namespace App\EntryPoint\Web\ChangePassword;

use App\Shared\UrlGenerator;
use App\User\Application\ChangePassword\Handler;
use App\User\Domain\PasswordHasherInterface;
use App\User\Domain\UserRepositoryInterface;
use App\Web\Identity\AuthenticatedUserProvider;
use App\Web\Layout\ContentNotices\ContentNotices;
use App\Web\ResponseFactory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;

final readonly class Action
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private AuthenticatedUserProvider $authenticatedUserProvider,
        private PasswordHasherInterface $passwordHasher,
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

        $form = new Form($user, $this->passwordHasher);
        if (!$this->formHydrator->populateFromPostAndValidate($form, $request)) {
            return $this->renderForm($form);
        }

        $this->handler->handle(
            $form->createCommand(),
        );

        $this->contentNotices->success('Password changed successfully.');
        return $this->responseFactory->temporarilyRedirect(
            $this->urlGenerator->changePassword(),
        );
    }

    private function renderForm(Form $form): ResponseInterface
    {
        return $this->responseFactory->render(
            __DIR__ . '/template.php',
            [
                'form' => $form,
            ],
        );
    }
}

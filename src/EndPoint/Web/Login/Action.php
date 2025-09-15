<?php

declare(strict_types=1);

namespace App\EndPoint\Web\Login;

use App\Shared\UrlGenerator;
use App\User\Application\TrySignIn\Handler;
use App\User\Domain\AuthKeyGeneratorInterface;
use App\EndPoint\Web\Shared\Identity\UserIdentity;
use App\EndPoint\Web\Shared\Layout\Layout;
use App\EndPoint\Web\Shared\ResponseFactory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\User\CurrentUser;
use Yiisoft\User\Login\Cookie\CookieLogin;

final readonly class Action
{
    public function __construct(
        private ResponseFactory $responseFactory,
        private CurrentUser $currentUser,
        private UrlGenerator $urlGenerator,
        private FormHydrator $formHydrator,
        private Handler $commandHandler,
        private AuthKeyGeneratorInterface $authKeyGenerator,
        private CookieLogin $cookieLogin,
    ) {}

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        if (!$this->currentUser->isGuest()) {
            return $this->responseFactory->temporarilyRedirect($this->urlGenerator->home());
        }

        $form = new Form();
        if (!$this->formHydrator->populateFromPostAndValidate($form, $request)) {
            return $this->renderForm($form);
        }

        $user = $this->commandHandler->handle(
            $form->createCommand(),
        );

        if ($user === null) {
            $form->addError(Form::ERROR_MESSAGE);
            return $this->renderForm($form);
        }

        $identity = new UserIdentity($user, $this->authKeyGenerator);
        if (!$this->currentUser->login($identity)) {
            $form->addError('Sign in is failed.');
            return $this->renderForm($form);
        }

        $response = $this->responseFactory->createResponse();
        if ($form->rememberMe) {
            $response = $this->cookieLogin->addCookie($identity, $response);
        }
        return $this->responseFactory->temporarilyRedirect($this->urlGenerator->home(), $response);
    }

    private function renderForm(Form $form): ResponseInterface
    {
        return $this->responseFactory->render(
            __DIR__ . '/template.php',
            ['form' => $form],
            Layout::PURE,
        );
    }
}

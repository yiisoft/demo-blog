<?php

declare(strict_types=1);

namespace App\EntryPoint\Web\Users\Update;

use App\Shared\UrlGenerator;
use App\User\Application\LoginAlreadyExistException;
use App\User\Application\UpdateUser\Handler;
use App\User\Domain\UserId;
use App\User\Domain\UserRepositoryInterface;
use App\Web\Identity\AuthenticatedUserProvider;
use App\Web\Layout\ContentNotices\ContentNotices;
use App\Web\ResponseFactory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\ErrorHandler\Exception\UserException;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Router\HydratorAttribute\RouteArgument;

use function sprintf;

final readonly class Action
{
    public function __construct(
        private FormHydrator $formHydrator,
        private Handler $handler,
        private UserRepositoryInterface $userRepository,
        private ContentNotices $contentNotices,
        private ResponseFactory $responseFactory,
        private UrlGenerator $urlGenerator,
        private AuthenticatedUserProvider $authenticatedUserProvider,
    ) {}

    public function __invoke(
        #[RouteArgument('id')]
        UserId $userId,
        ServerRequestInterface $request,
    ): ResponseInterface {
        $user = $this->userRepository->getOrUserException($userId);

        $form = new Form($user);

        if (!$this->formHydrator->populateFromPostAndValidate($form, $request)) {
            return $this->renderForm($form);
        }

        $command = $form->createCommand();
        if ($command->status !== $user->status
            && $this->authenticatedUserProvider->getId()->isEqualTo($user->id)
        ) {
            throw new UserException('Cannot change status for current user.');
        }

        try {
            $this->handler->handle($form->createCommand());
        } catch (LoginAlreadyExistException $exception) {
            $form->getValidationResult()->addError($exception->getMessage(), valuePath: ['login']);
            return $this->renderForm($form);
        }

        $this->contentNotices->success(
            sprintf(
                'User "%s" is updated.',
                $form->login,
            ),
        );
        return $this->responseFactory->temporarilyRedirect($this->urlGenerator->generate('users/index'));
    }

    private function renderForm(Form $form): ResponseInterface
    {
        return $this->responseFactory->render(
            __DIR__ . '/template.php',
            ['form' => $form],
        );
    }
}

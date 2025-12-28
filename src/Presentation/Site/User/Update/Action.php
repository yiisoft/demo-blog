<?php

declare(strict_types=1);

namespace App\Presentation\Site\User\Update;

use App\Application\User\LoginAlreadyExistException;
use App\Application\User\UpdateUser\Handler;
use App\Domain\User\UserId;
use App\Domain\User\UserRepositoryInterface;
use App\Presentation\Site\Shared\Access\RbacManager;
use App\Presentation\Site\Shared\Identity\AuthenticatedUserProvider;
use App\Presentation\Site\Shared\Layout\ContentNotices\ContentNotices;
use App\Presentation\Site\Shared\ResponseFactory\ResponseFactory;
use App\Presentation\Site\Shared\ResponseFactory\ValidateOrNotFound\ValidateOrNotFound;
use App\Shared\UrlGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\ErrorHandler\Exception\UserException;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Router\HydratorAttribute\RouteArgument;
use Yiisoft\Validator\Rule\Uuid;

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
        private RbacManager $rbacManager,
    ) {}

    /**
     * @throws UserException
     */
    public function __invoke(
        #[RouteArgument('id')]
        #[ValidateOrNotFound(new Uuid())]
        UserId $userId,
        ServerRequestInterface $request,
    ): ResponseInterface {
        $user = $this->userRepository->getOrUserException($userId);

        $currentRole = $this->rbacManager->tryGetRoleByUserId($user->id);
        $form = new Form(
            $user,
            $currentRole,
            $this->authenticatedUserProvider->getId()->isEqualTo($user->id),
        );

        if (!$this->formHydrator->populateFromPostAndValidate($form, $request)) {
            return $this->renderForm($form);
        }

        $command = $form->createCommand();
        if ($form->isCurrentUser) {
            if ($command->status !== $user->status) {
                throw new UserException('Cannot change status for current user.');
            }
            if ($command->role !== $currentRole) {
                throw new UserException('Cannot change role for current user.');
            }
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
        return $this->responseFactory->temporarilyRedirect($this->urlGenerator->generate('user/index'));
    }

    private function renderForm(Form $form): ResponseInterface
    {
        return $this->responseFactory->render(
            __DIR__ . '/template.php',
            ['form' => $form],
        );
    }
}

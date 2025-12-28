<?php

declare(strict_types=1);

namespace App\Presentation\Site\User\Delete;

use App\Application\User\DeleteUser\Command;
use App\Application\User\DeleteUser\Handler;
use App\Domain\User\UserId;
use App\Domain\User\UserRepositoryInterface;
use App\Presentation\Site\Shared\Identity\AuthenticatedUserProvider;
use App\Presentation\Site\Shared\Layout\ContentNotices\ContentNotices;
use App\Presentation\Site\Shared\ResponseFactory\ResponseFactory;
use App\Presentation\Site\Shared\ResponseFactory\ValidateOrNotFound\ValidateOrNotFound;
use App\Shared\UrlGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\ErrorHandler\Exception\UserException;
use Yiisoft\Http\Method;
use Yiisoft\Router\HydratorAttribute\RouteArgument;
use Yiisoft\Validator\Rule\Uuid;

use function sprintf;

final readonly class Action
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private Handler $handler,
        private AuthenticatedUserProvider $authenticatedUserProvider,
        private ContentNotices $contentNotices,
        private ResponseFactory $responseFactory,
        private UrlGenerator $urlGenerator,
    ) {}

    public function __invoke(
        #[RouteArgument('id')]
        #[ValidateOrNotFound(new Uuid())]
        UserId $userId,
        ServerRequestInterface $request,
    ): ResponseInterface {
        $user = $this->userRepository->getOrUserException($userId);

        if ($this->authenticatedUserProvider->getId()->isEqualTo($userId)) {
            throw new UserException('Cannot delete current user.');
        }

        if ($request->getMethod() !== Method::POST) {
            return $this->responseFactory->render(
                __DIR__ . '/template.php',
                ['user' => $user],
            );
        }

        $this->handler->handle(new Command($userId));

        $this->contentNotices->success(
            sprintf(
                'User "%s" with ID "%s" is deleted.',
                $user->login,
                $user->id,
            ),
        );
        return $this->responseFactory->temporarilyRedirect($this->urlGenerator->generate('user/index'));
    }
}

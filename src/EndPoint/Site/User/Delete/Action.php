<?php

declare(strict_types=1);

namespace App\EndPoint\Site\User\Delete;

use App\EndPoint\Site\Shared\Identity\AuthenticatedUserProvider;
use App\EndPoint\Site\Shared\Layout\ContentNotices\ContentNotices;
use App\EndPoint\Site\Shared\ResponseFactory\ResponseFactory;
use App\EndPoint\Site\Shared\ResponseFactory\ValidateOrNotFound\ValidateOrNotFound;
use App\Shared\Infrastructure\UrlGenerator;
use App\User\Application\DeleteUser\Command;
use App\User\Application\DeleteUser\Handler;
use App\User\Domain\UserId;
use App\User\Domain\UserRepositoryInterface;
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

<?php

declare(strict_types=1);

namespace App\EntryPoint\Web\Users\ChangePassword;

use App\Shared\UrlGenerator;
use App\User\Application\ChangePassword\Handler;
use App\User\Domain\UserId;
use App\User\Domain\UserRepositoryInterface;
use App\Web\Layout\ContentNotices\ContentNotices;
use App\Web\ResponseFactory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Router\HydratorAttribute\RouteArgument;

use function sprintf;

final readonly class Action
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private ResponseFactory $responseFactory,
        private FormHydrator $formHydrator,
        private Handler $handler,
        private UrlGenerator $urlGenerator,
        private ContentNotices $contentNotices,
    ) {}

    public function __invoke(
        #[RouteArgument('id')]
        UserId $userId,
        ServerRequestInterface $request,
    ): ResponseInterface {
        $user = $this->userRepository->getOrUserException($userId);

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

        $this->contentNotices->success(sprintf('Password for user "%s" changed successfully.', $user->login));
        return $this->responseFactory->temporarilyRedirect(
            $this->urlGenerator->generate('users/index'),
        );
    }
}

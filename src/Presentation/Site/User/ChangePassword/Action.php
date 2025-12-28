<?php

declare(strict_types=1);

namespace App\Presentation\Site\User\ChangePassword;

use App\Application\User\ChangePassword\Handler;
use App\Domain\User\UserId;
use App\Domain\User\UserRepositoryInterface;
use App\Presentation\Site\Shared\Layout\ContentNotices\ContentNotices;
use App\Presentation\Site\Shared\ResponseFactory\ResponseFactory;
use App\Presentation\Site\Shared\ResponseFactory\ValidateOrNotFound\ValidateOrNotFound;
use App\Shared\UrlGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Router\HydratorAttribute\RouteArgument;
use Yiisoft\Validator\Rule\Uuid;

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
        #[ValidateOrNotFound(new Uuid())]
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
            $this->urlGenerator->generate('user/index'),
        );
    }
}

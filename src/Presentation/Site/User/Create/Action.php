<?php

declare(strict_types=1);

namespace App\Presentation\Site\User\Create;

use App\Application\User\CreateUser\Handler;
use App\Application\User\LoginAlreadyExistException;
use App\Presentation\Site\Shared\Layout\ContentNotices\ContentNotices;
use App\Presentation\Site\Shared\ResponseFactory\ResponseFactory;
use App\Shared\UrlGenerator;
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
    ) {}

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $form = new Form();

        if (!$this->formHydrator->populateFromPostAndValidate($form, $request)) {
            return $this->renderForm($form);
        }

        try {
            $result = $this->handler->handle($form->createCommand());
        } catch (LoginAlreadyExistException $exception) {
            $form->getValidationResult()->addError($exception->getMessage(), valuePath: ['login']);
            return $this->renderForm($form);
        }

        $this->contentNotices->success(
            sprintf(
                'User "%s" with ID "%s" is created.',
                $form->login,
                $result->id,
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

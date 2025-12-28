<?php

declare(strict_types=1);

namespace App\Presentation\Site\Login;

use App\Application\User\TrySignIn\Command;
use App\Domain\User\Login;
use App\Domain\User\Password;
use Yiisoft\FormModel\Attribute\Safe;
use Yiisoft\FormModel\FormModel;
use Yiisoft\Hydrator\Attribute\Parameter\Trim;
use Yiisoft\Validator\Result;
use Yiisoft\Validator\Rule\Callback;
use Yiisoft\Validator\Rule\Required;

final class Form extends FormModel
{
    public const ERROR_MESSAGE = 'Incorrect login or password.';

    #[Trim]
    #[Required]
    public string $login = '';

    #[Required]
    #[Callback(method: 'validateLoginAndPassword', skipOnError: true)]
    public string $password = '';

    #[Safe]
    public bool $rememberMe = false;

    private function validateLoginAndPassword(): Result
    {
        $result = new Result();
        if (mb_strlen($this->login) > Login::LENGTH_LIMIT
            || mb_strlen($this->password) < Password::LENGTH_MIN
            || mb_strlen($this->password) > Password::LENGTH_MAX
        ) {
            $result->addError(self::ERROR_MESSAGE);
        }
        return $result;
    }

    /**
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function createCommand(): Command
    {
        return new Command(
            new Login($this->login),
            new Password($this->password),
        );
    }

    public function getFormName(): string
    {
        return '';
    }
}

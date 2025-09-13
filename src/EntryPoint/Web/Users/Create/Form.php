<?php

declare(strict_types=1);

namespace App\EntryPoint\Web\Users\Create;

use App\User\Application\CreateUser\Command;
use App\User\Domain\Login;
use App\User\Domain\Password;
use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Required;

final class Form extends FormModel
{
    #[Required]
    #[Length(max: Login::LENGTH_LIMIT)]
    public string $login = '';

    #[Required]
    #[Length(min: Password::LENGTH_MIN, max: Password::LENGTH_MAX)]
    public string $password = '';

    /**
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function createCommand(): Command
    {
        return new Command(
            login: new Login($this->login),
            password: new Password($this->password),
        );
    }
}

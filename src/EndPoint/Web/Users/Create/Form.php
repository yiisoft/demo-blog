<?php

declare(strict_types=1);

namespace App\EndPoint\Web\Users\Create;

use App\User\Application\CreateUser\Command;
use App\User\Domain\Login;
use App\User\Domain\UserName;
use App\User\Domain\Password;
use App\Web\Access\Role;
use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Required;

final class Form extends FormModel
{
    #[Required]
    #[Length(max: Login::LENGTH_LIMIT)]
    public string $login = '';

    #[Required]
    #[Length(max: UserName::LENGTH_LIMIT)]
    public string $name = '';

    #[Required]
    #[Length(min: Password::LENGTH_MIN, max: Password::LENGTH_MAX)]
    public string $password = '';

    #[Required]
    public Role|null $role = null;

    /**
     * @psalm-suppress ArgumentTypeCoercion, PossiblyNullArgument
     */
    public function createCommand(): Command
    {
        return new Command(
            login: new Login($this->login),
            name: new UserName($this->name),
            password: new Password($this->password),
            role: $this->role,
        );
    }
}

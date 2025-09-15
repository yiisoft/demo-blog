<?php

declare(strict_types=1);

namespace App\EndPoint\Console\CreateAdmin;

use App\User\Application\CreateUser\Command;
use App\User\Domain\Login;
use App\User\Domain\Password;
use App\User\Domain\UserName;
use App\EndPoint\Web\Shared\Access\Role;
use Symfony\Component\Console\Input\InputInterface;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Required;

final readonly class Model
{
    #[Required(message: 'Login cannot be blank.')]
    #[Length(max: Login::LENGTH_LIMIT)]
    public string $login;

    #[Required]
    #[Length(min: Password::LENGTH_MIN, max: Password::LENGTH_MAX)]
    public string $password;

    public function __construct(InputInterface $input)
    {
        $this->login = $input->getArgument('login');
        $this->password = $input->getArgument('password');
    }

    /**
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function createCommand(): Command
    {
        return new Command(
            login: new Login($this->login),
            name: new UserName($this->login),
            password: new Password($this->password),
            role: Role::Admin,
        );
    }
}

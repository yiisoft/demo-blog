<?php

declare(strict_types=1);

namespace App\EndPoint\Web\Users\Update;

use App\User\Application\UpdateUser\Command;
use App\User\Domain\Login;
use App\User\Domain\UserName;
use App\User\Domain\User;
use App\User\Domain\UserStatus;
use App\Web\Access\Role;
use Yiisoft\FormModel\Attribute\Safe;
use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Required;

final class Form extends FormModel
{
    #[Required]
    #[Length(max: Login::LENGTH_LIMIT)]
    public string $login;

    #[Required]
    #[Length(max: UserName::LENGTH_LIMIT)]
    public string $name;

    #[Safe]
    public UserStatus $status;

    #[Required]
    public Role|null $role;

    public function __construct(
        public readonly User $user,
        Role|null $role,
        public readonly bool $isCurrentUser,
    ) {
        $this->login = $user->login->toString();
        $this->name = $user->name->toString();
        $this->status = $user->status;
        $this->role = $role;
    }

    /**
     * @psalm-suppress ArgumentTypeCoercion, PossiblyNullArgument
     */
    public function createCommand(): Command
    {
        return new Command(
            id: $this->user->id,
            login: new Login($this->login),
            name: new UserName($this->name),
            status: $this->status,
            role: $this->role,
        );
    }
}

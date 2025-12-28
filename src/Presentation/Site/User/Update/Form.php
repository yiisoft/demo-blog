<?php

declare(strict_types=1);

namespace App\Presentation\Site\User\Update;

use App\Application\User\UpdateUser\Command;
use App\Domain\User\Login;
use App\Domain\User\User;
use App\Domain\User\UserName;
use App\Domain\User\UserStatus;
use App\Presentation\Site\Shared\Access\Role;
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

    public function __construct(
        public readonly User $user,
        #[Required]
        public Role|null $role,
        public readonly bool $isCurrentUser,
    ) {
        $this->login = $user->login->toString();
        $this->name = $user->name->toString();
        $this->status = $user->status;
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

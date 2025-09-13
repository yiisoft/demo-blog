<?php

declare(strict_types=1);

namespace App\EntryPoint\Web\Users\Update;

use App\User\Application\UpdateUser\Command;
use App\User\Domain\Login;
use App\User\Domain\User;
use App\User\Domain\UserId;
use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Required;

final class Form extends FormModel
{
    #[Required]
    #[Length(max: Login::LENGTH_LIMIT)]
    public string $login = '';

    public readonly UserId $userId;

    public function __construct(User $user)
    {
        $this->login = $user->login->asString();
        $this->userId = $user->id;
    }

    /**
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function createCommand(): Command
    {
        return new Command(
            id: $this->userId,
            login: new Login($this->login),
        );
    }
}

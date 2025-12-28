<?php

declare(strict_types=1);

namespace App\Presentation\Site\User\ChangePassword;

use App\Application\User\ChangePassword\Command;
use App\Domain\User\Password;
use App\Domain\User\User;
use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Label;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Required;

final class Form extends FormModel
{
    #[Label('New password')]
    #[Required]
    #[Length(
        min: Password::LENGTH_MIN,
        max: Password::LENGTH_MAX,
        skipOnEmpty: true,
    )]
    public string $password = '';

    public function __construct(
        public readonly User $user,
    ) {}

    /**
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function createCommand(): Command
    {
        return new Command(
            userId: $this->user->id,
            password: new Password($this->password),
        );
    }
}

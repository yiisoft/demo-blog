<?php

declare(strict_types=1);

namespace App\EntryPoint\Web\Profile\ChangePassword;

use App\User\Application\ChangePassword\Command;
use App\User\Domain\Password;
use App\User\Domain\PasswordHasherInterface;
use App\User\Domain\User;
use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Label;
use Yiisoft\Validator\Result;
use Yiisoft\Validator\Rule\Callback;
use Yiisoft\Validator\Rule\CompareType;
use Yiisoft\Validator\Rule\Equal;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Required;

final class Form extends FormModel
{
    #[Label('Current password')]
    #[Required]
    #[Length(
        min: Password::LENGTH_MIN,
        max: Password::LENGTH_MAX,
        skipOnEmpty: true,
    )]
    #[Callback(method: 'checkCurrentPassword', skipOnError: true)]
    public string $current = '';

    #[Label('New password')]
    #[Required]
    #[Length(
        min: Password::LENGTH_MIN,
        max: Password::LENGTH_MAX,
        skipOnEmpty: true,
    )]
    public string $new = '';

    #[Label('Once again')]
    #[Required]
    #[Equal(targetProperty: 'new', type: CompareType::STRING)]
    public string $new2 = '';

    public function __construct(
        private readonly User $user,
        private readonly PasswordHasherInterface $passwordHasher,
    ) {}

    /**
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function checkCurrentPassword(string $value): Result
    {
        if ($this->user->isValidPassword(new Password($value), $this->passwordHasher)) {
            return new Result();
        }
        return new Result()->addError('Invalid current password.');
    }

    /**
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function createCommand(): Command
    {
        return new Command(
            $this->user->id,
            new Password($this->new),
        );
    }
}

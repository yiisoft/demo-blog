<?php

declare(strict_types=1);

namespace App\EndPoint\Web\Profile\UpdateProfile;

use App\User\Application\UpdateProfile\Command;
use App\User\Domain\User;
use App\User\Domain\UserName;
use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Required;

final class Form extends FormModel
{
    #[Required]
    #[Length(max: UserName::LENGTH_LIMIT)]
    public string $name = '';

    public function __construct(
        private readonly User $user,
    ) {
        $this->name = $user->name->toString();
    }

    /**
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function createCommand(): Command
    {
        return new Command(
            userId: $this->user->id,
            name: new UserName($this->name),
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Presentation\Site\Shared\Identity;

use App\Domain\User\UserId;
use LogicException;
use Yiisoft\User\CurrentUser;

final readonly class AuthenticatedUserProvider
{
    public function __construct(
        private CurrentUser $currentUser,
    ) {}

    public function getId(): UserId
    {
        $rawId = $this->currentUser->getId();
        if ($rawId === null) {
            throw new LogicException('User is not authenticated.');
        }

        return UserId::fromString($rawId);
    }
}

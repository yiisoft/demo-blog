<?php

declare(strict_types=1);

namespace App\Web\Identity;

use App\User\Domain\AuthKeyGeneratorInterface;
use App\User\Domain\UserId;
use App\User\Domain\UserRepositoryInterface;
use Yiisoft\Auth\IdentityInterface;
use Yiisoft\Auth\IdentityRepositoryInterface;

final readonly class IdentityRepository implements IdentityRepositoryInterface
{
    public function __construct(
        private AuthKeyGeneratorInterface $authKeyGenerator,
        private UserRepositoryInterface $userRepository,
    ) {}

    public function findIdentity(string $id): ?IdentityInterface
    {
        $userId = UserId::tryFromString($id);
        if ($userId === null) {
            return null;
        }

        $user = $this->userRepository->tryGet($userId);
        if ($user === null) {
            return null;
        }

        return new UserIdentity($user, $this->authKeyGenerator);
    }
}

<?php

declare(strict_types=1);

namespace App\Presentation\Site\Shared\Identity;

use App\Domain\User\AuthKeyGeneratorInterface;
use App\Domain\User\UserId;
use App\Domain\User\UserRepositoryInterface;
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
        if ($user === null || !$user->canSignIn()) {
            return null;
        }

        return new UserIdentity($user, $this->authKeyGenerator);
    }
}

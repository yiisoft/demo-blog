<?php

declare(strict_types=1);

namespace App\User\Application\TrySignIn;

use App\User\Domain\AuthKeyGeneratorInterface;
use App\User\Domain\PasswordHasherInterface;
use App\User\Domain\User;
use App\User\Domain\UserRepositoryInterface;

final readonly class Handler
{
    public function __construct(
        private PasswordHasherInterface $passwordHasher,
        private UserRepositoryInterface $userRepository,
        private AuthKeyGeneratorInterface $authKeyGenerator,
    ) {}

    public function handle(Command $command): ?User
    {
        $user = $this->userRepository->tryGetByLogin($command->login);
        if ($user === null) {
            return null;
        }
        if (!$user->isValidPassword($command->password, $this->passwordHasher)) {
            return null;
        }

        $user->generateAuthKey($this->authKeyGenerator);
        $this->userRepository->update($user);

        return $user;
    }
}

<?php

declare(strict_types=1);

namespace App\Application\User\TrySignIn;

use App\Domain\User\AuthKeyGeneratorInterface;
use App\Domain\User\PasswordHasherInterface;
use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterface;

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
        if ($user === null
            || !$user->canSignIn()
            || !$user->isValidPassword($command->password, $this->passwordHasher)
        ) {
            return null;
        }

        $user->generateAuthKey($this->authKeyGenerator);
        $this->userRepository->update($user);

        return $user;
    }
}

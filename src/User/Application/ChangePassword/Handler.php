<?php

declare(strict_types=1);

namespace App\User\Application\ChangePassword;

use App\User\Domain\PasswordHasherInterface;
use App\User\Domain\UserRepositoryInterface;

final readonly class Handler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasherInterface $passwordHasher,
    ) {}

    public function handle(Command $command): void
    {
        $user = $this->userRepository->getOrUserException($command->userId);
        $user->changePassword($command->password, $this->passwordHasher);
        $this->userRepository->update($user);
    }
}

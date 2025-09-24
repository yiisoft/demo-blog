<?php

declare(strict_types=1);

namespace App\User\Application\UpdateProfile;

use App\User\Domain\UserRepositoryInterface;

final readonly class Handler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function handle(Command $command): void
    {
        $user = $this->userRepository->getOrUserException($command->userId);
        $user->changeName($command->name);
        $this->userRepository->update($user);
    }
}

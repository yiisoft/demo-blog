<?php

declare(strict_types=1);

namespace App\User\Application\DeleteUser;

use App\User\Domain\UserRepositoryInterface;

final readonly class Handler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function handle(Command $command): void
    {
        $this->userRepository->delete($command->id);
    }
}

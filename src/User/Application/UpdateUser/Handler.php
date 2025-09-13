<?php

declare(strict_types=1);

namespace App\User\Application\UpdateUser;

use App\User\Domain\UserRepositoryInterface;
use App\User\Application\LoginAlreadyExistException;

final readonly class Handler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    /**
     * @throws LoginAlreadyExistException
     */
    public function handle(Command $command): void
    {
        $user = $this->userRepository->getOrUserException($command->id);

        if (!$user->login->isEqualTo($command->login)
            && $this->userRepository->hasByLogin($command->login)
        ) {
            throw new LoginAlreadyExistException($command->login);
        }

        $user->changeLogin($command->login);
        $user->changeName($command->name);

        $this->userRepository->update($user);
    }
}

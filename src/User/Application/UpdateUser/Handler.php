<?php

declare(strict_types=1);

namespace App\User\Application\UpdateUser;

use App\User\Domain\UserRepositoryInterface;
use App\User\Application\LoginAlreadyExistException;
use App\User\Domain\UserStatus;
use App\EndPoint\Web\Shared\Access\RbacManager;
use Yiisoft\Db\Connection\ConnectionInterface;

final readonly class Handler
{
    public function __construct(
        private ConnectionInterface $db,
        private UserRepositoryInterface $userRepository,
        private RbacManager $rbacManager,
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
        match ($command->status) {
            UserStatus::Active => $user->activate(),
            UserStatus::Inactive => $user->deactivate(),
        };

        $this->db->transaction(
            function () use ($user, $command) {
                $this->userRepository->update($user);
                $this->rbacManager->changeRole($user->id, $command->role);
            },
        );
    }
}

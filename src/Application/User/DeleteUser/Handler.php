<?php

declare(strict_types=1);

namespace App\Application\User\DeleteUser;

use App\Domain\Post\PostRepositoryInterface;
use App\Domain\User\UserRepositoryInterface;

final readonly class Handler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PostRepositoryInterface $postRepository,
    ) {}

    /**
     * @throws UserDeletionConstraintException
     */
    public function handle(Command $command): void
    {
        if ($this->postRepository->hasByCreatedByOrUpdatedBy($command->id)) {
            throw new UserDeletionConstraintException('Cannot delete user with existing posts.');
        }

        $this->userRepository->delete($command->id);
    }
}

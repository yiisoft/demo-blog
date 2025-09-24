<?php

declare(strict_types=1);

namespace App\User\Application\DeleteUser;

use App\Blog\Domain\Post\PostRepositoryInterface;
use App\User\Domain\UserRepositoryInterface;

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

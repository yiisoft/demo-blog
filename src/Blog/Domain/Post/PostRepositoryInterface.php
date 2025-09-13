<?php

declare(strict_types=1);

namespace App\Blog\Domain\Post;

interface PostRepositoryInterface
{
    public function getOrUserException(PostId $id): Post;

    public function add(Post $post): void;

    public function update(Post $post): void;

    public function delete(PostId $id): void;
}

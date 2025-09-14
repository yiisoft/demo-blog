<?php

declare(strict_types=1);

namespace App\Blog\Application;

use App\Blog\Domain\Post\PostSlug;
use Exception;
use Yiisoft\ErrorHandler\Exception\UserException;

use function sprintf;

#[UserException]
final class SlugAlreadyExistException extends Exception
{
    public function __construct(PostSlug $slug)
    {
        parent::__construct(
            sprintf('Post with slug "%s" already exist.', $slug),
        );
    }
}

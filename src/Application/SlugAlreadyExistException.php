<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\Category\CategorySlug;
use App\Domain\Post\PostSlug;
use Exception;
use Yiisoft\ErrorHandler\Exception\UserException;

use function sprintf;

#[UserException]
final class SlugAlreadyExistException extends Exception
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function fromPostSlug(PostSlug $slug): self
    {
        return new self(sprintf('Post with slug "%s" already exist.', $slug));
    }

    public static function fromCategorySlug(CategorySlug $slug): self
    {
        return new self(sprintf('Category with slug "%s" already exist.', $slug));
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Category;

use Stringable;
use Webmozart\Assert\Assert;

final readonly class CategoryName implements Stringable
{
    public const int LENGTH_LIMIT = 100;

    /**
     * @param non-empty-string $value
     */
    public function __construct(
        private string $value,
    ) {
        Assert::maxLength($value, self::LENGTH_LIMIT);
    }

    /**
     * @return non-empty-string
     */
    public function toString(): string
    {
        return $this->value;
    }

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}

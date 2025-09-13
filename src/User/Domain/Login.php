<?php

declare(strict_types=1);

namespace App\User\Domain;

use Stringable;
use Webmozart\Assert\Assert;

final readonly class Login implements Stringable
{
    public const int LENGTH_LIMIT = 50;

    /**
     * @param non-empty-string $value
     */
    public function __construct(
        private string $value,
    ) {
        Assert::maxLength($value, self::LENGTH_LIMIT);
    }

    public function isEqualTo(self $other): bool
    {
        return $this->value === $other->value;
    }

    /**
     * @return non-empty-string
     */
    public function asString(): string
    {
        return $this->value;
    }

    /**
     * @return non-empty-string
     */
    public function __toString()
    {
        return $this->asString();
    }
}

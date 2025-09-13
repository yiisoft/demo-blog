<?php

declare(strict_types=1);

namespace App\User\Domain;

use SensitiveParameter;
use Webmozart\Assert\Assert;

final readonly class Password
{
    public const int LENGTH_MIN = 8;
    public const int LENGTH_MAX = 96;

    /**
     * @param non-empty-string $value
     */
    public function __construct(
        #[SensitiveParameter]
        private string $value,
    ) {
        Assert::lengthBetween($value, self::LENGTH_MIN, self::LENGTH_MAX);
    }

    public function toString(): string
    {
        return $this->value;
    }
}

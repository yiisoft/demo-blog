<?php

declare(strict_types=1);

namespace App\Domain\Post;

use Webmozart\Assert\Assert;

final readonly class PostSlug implements \Stringable
{
    public const int LENGTH_LIMIT = 255;

    /**
     * @param non-empty-string $value
     */
    public function __construct(
        private string $value,
    ) {
        Assert::maxLength($value, self::LENGTH_LIMIT);
    }

    public static function tryFromString(string $value): self|null
    {
        $length = mb_strlen($value);
        return $value === '' || $length > self::LENGTH_LIMIT
            ? null
            : new self($value);
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

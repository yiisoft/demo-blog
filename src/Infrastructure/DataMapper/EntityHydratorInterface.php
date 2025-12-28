<?php

declare(strict_types=1);

namespace App\Infrastructure\DataMapper;

interface EntityHydratorInterface
{
    /**
     * @template T as object
     * @param class-string<T> $class
     * @return T
     */
    public function create(string $class, array $data): object;
}

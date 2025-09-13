<?php

declare(strict_types=1);

namespace App\Shared\DataMapper;

use ReflectionClass;
use Yiisoft\Hydrator\Hydrator;

final readonly class YiiHydratorEntityHydrator implements EntityHydratorInterface
{
    private Hydrator $hydrator;

    public function __construct()
    {
        $this->hydrator = new Hydrator();
    }

    public function create(string $class, array $data): object
    {
        $object = new ReflectionClass($class)->newInstanceWithoutConstructor();
        $this->hydrator->hydrate($object, $data);
        return $object;
    }
}

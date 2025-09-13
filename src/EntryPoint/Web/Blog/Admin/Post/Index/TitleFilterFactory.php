<?php

declare(strict_types=1);

namespace App\EntryPoint\Web\Blog\Admin\Post\Index;

use Ramsey\Uuid\Uuid;
use Yiisoft\Data\Reader\Filter\Equals;
use Yiisoft\Data\Reader\Filter\Like;
use Yiisoft\Data\Reader\FilterInterface;
use Yiisoft\Yii\DataView\Filter\Factory\FilterFactoryInterface;

final readonly class TitleFilterFactory implements FilterFactoryInterface
{
    public function create(string $property, string $value): FilterInterface
    {
        return Uuid::isValid($value)
            ? new Equals('id', $value)
            : new Like('title', $value, caseSensitive: false);
    }
}

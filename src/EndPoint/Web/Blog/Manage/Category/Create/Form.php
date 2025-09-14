<?php

declare(strict_types=1);

namespace App\EndPoint\Web\Blog\Manage\Category\Create;

use App\Blog\Application\CreateCategory\Command;
use App\Blog\Domain\Category\CategoryName;
use App\Blog\Domain\Category\CategorySlug;
use Yiisoft\FormModel\Attribute\Safe;
use Yiisoft\FormModel\FormModel;
use Yiisoft\Hydrator\Attribute\Parameter\Trim;
use Yiisoft\Strings\Inflector;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Required;

final class Form extends FormModel
{
    #[Trim]
    #[Required]
    #[Length(max: CategoryName::LENGTH_LIMIT)]
    public string $name = '';

    #[Trim]
    #[Safe]
    public string $description = '';

    #[Trim]
    #[Length(max: CategorySlug::LENGTH_LIMIT)]
    public string $slug = '';

    /**
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function createCommand(Inflector $inflector): Command
    {
        if ($this->slug === '') {
            $this->slug = mb_substr($inflector->toSlug($this->name), 0, CategorySlug::LENGTH_LIMIT);
        }
        return new Command(
            name: new CategoryName($this->name),
            description: $this->description,
            slug: new CategorySlug($this->slug),
        );
    }
}

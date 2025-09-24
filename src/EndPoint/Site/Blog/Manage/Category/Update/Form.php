<?php

declare(strict_types=1);

namespace App\EndPoint\Site\Blog\Manage\Category\Update;

use App\Blog\Application\UpdateCategory\Command;
use App\Blog\Domain\Category\Category;
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
    public string $name;

    #[Trim]
    #[Safe]
    public string $description;

    #[Trim]
    #[Length(max: CategorySlug::LENGTH_LIMIT)]
    public string $slug;

    public function __construct(
        public readonly Category $category,
    ) {
        $this->name = $this->category->name->toString();
        $this->description = $this->category->description;
        $this->slug = $this->category->slug->toString();
    }

    /**
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function createCommand(Inflector $inflector): Command
    {
        if ($this->slug === '') {
            $this->slug = mb_substr($inflector->toSlug($this->name), 0, CategorySlug::LENGTH_LIMIT);
        }
        return new Command(
            id: $this->category->id,
            name: new CategoryName($this->name),
            description: $this->description,
            slug: new CategorySlug($this->slug),
        );
    }
}

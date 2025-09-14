<?php

declare(strict_types=1);

namespace App\EndPoint\Web\Blog\Listing\CategoriesPanel;

use App\EndPoint\Web\Blog\Listing\CategoryReader\Category;
use Yiisoft\View\WebView;
use Yiisoft\Widget\Widget;

final class CategoriesPanel extends Widget
{
    public function __construct(
        /** @var list<Category> */
        private readonly array $categories,
        private readonly WebView $view,
    ) {}

    /**
     * @param list<Category> $categories
     */
    public static function create(array $categories): self
    {
        return self::widget([$categories]);
    }


    public function render(): string
    {
        if (empty($this->categories)) {
            return '';
        }

        return $this->view->render(
            __DIR__ . '/template.php',
            ['categories' => $this->categories],
        );
    }
}

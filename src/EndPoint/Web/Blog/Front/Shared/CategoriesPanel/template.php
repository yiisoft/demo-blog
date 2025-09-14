<?php

declare(strict_types=1);

use App\Blog\Domain\Category\CategoryId;
use App\EndPoint\Web\Blog\Front\Shared\CategoryReader\Category;
use App\Shared\UrlGenerator;
use Yiisoft\Html\Html;

/**
 * @var UrlGenerator $urlGenerator
 * @var non-empty-list<Category> $categories
 * @var CategoryId|null $currentCategoryId
 */
?>
<div class="d-flex flex-wrap gap-2 mb-4">
    <?php foreach ($categories as $category) { ?>
        <?= Html::a(
            Html::encode($category->name)
            . ' '
            . Html::span(
                (string) $category->countPosts,
                ['class' => 'badge bg-light text-dark'],
            ),
            $urlGenerator->category($category->slug),
            [
                'class' => [
                    'btn btn-light btn-sm d-flex align-items-center gap-2',
                    $currentCategoryId?->isEqualTo($category->id) ? 'active' : null,
                ],
            ],
        )->encode(false) ?>
    <?php } ?>
</div>

<?php

declare(strict_types=1);

use App\EndPoint\Web\Blog\Listing\CategoryReader\Category;
use Yiisoft\Html\Html;

/**
 * @var non-empty-list<Category> $categories
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
            '#',
            ['class' => 'btn btn-light btn-sm d-flex align-items-center gap-2'],
        )->encode(false) ?>
    <?php } ?>
</div>

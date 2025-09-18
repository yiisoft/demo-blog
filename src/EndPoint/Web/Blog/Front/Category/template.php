<?php

declare(strict_types=1);

use App\Blog\Domain\Category\Category as DomainCategory;
use App\EndPoint\Web\Blog\Front\Shared\CategoriesPanel\CategoriesPanel;
use App\EndPoint\Web\Blog\Front\Shared\CategoryReader\Category;
use App\EndPoint\Web\Blog\Front\Shared\PostsList\PostsList;
use App\EndPoint\Web\Shared\Access\Permission;
use App\Shared\Formatter;
use App\Shared\UrlGenerator;
use App\EndPoint\Web\Shared\Layout\Breadcrumbs\Breadcrumb;
use Yiisoft\Data\Paginator\OffsetPaginator;
use Yiisoft\Html\Html;
use Yiisoft\Html\NoEncode;
use Yiisoft\User\CurrentUser;
use Yiisoft\View\WebView;

/**
 * @var WebView $this
 * @var UrlGenerator $urlGenerator
 * @var CurrentUser $currentUser
 * @var Formatter $formatter
 * @var DomainCategory $category
 * @var OffsetPaginator $paginator
 * @var list<Category> $categories
 */

$this->setTitle($category->name . ' / Blog');
$this->addToParameter(
    'breadcrumbs',
    new Breadcrumb('Blog', urlName: 'blog/post/index'),
    new Breadcrumb(
        $category->name,
        urlName: $paginator->isOnFirstPage() ? null : 'blog/category/index',
        urlParameters: ['slug' => $category->slug],
    ),
);
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><?= Html::encode($category->name) ?></h1>
    <?php if ($currentUser->can(Permission::BlogManage)): ?>
        <div>
            <?= Html::a(
                NoEncode::string('<i class="bi bi-pencil me-1"></i> Edit'),
                $urlGenerator->categoryUpdate($category->id),
                ['class' => 'btn btn-outline-primary btn-sm'],
            ) ?>
        </div>
    <?php endif; ?>
</div>
<?php if ($category->description !== '') { ?>
    <div class="mb-4">
        <p class="text-secondary mb-0">
            <?= nl2br(Html::encode($category->description)) ?>
        </p>
    </div>
<?php } ?>
<div class="mb-5">
    <?= PostsList::widget([$paginator]) ?>
</div>
<?= CategoriesPanel::create($categories, $category->id) ?>

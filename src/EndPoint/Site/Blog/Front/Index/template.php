<?php

declare(strict_types=1);

use App\EndPoint\Site\Blog\Front\Shared\CategoriesPanel\CategoriesPanel;
use App\Blog\Read\RichCategories\Category;
use App\EndPoint\Site\Blog\Front\Shared\PostsList\PostsList;
use App\Shared\Formatter;
use App\Shared\UrlGenerator;
use App\EndPoint\Site\Shared\Access\Permission;
use App\EndPoint\Site\Shared\Layout\Breadcrumbs\Breadcrumb;
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
 * @var OffsetPaginator $paginator
 * @var list<Category> $categories
 */

$this->setTitle('Blog');
$this->addToParameter(
    'breadcrumbs',
    new Breadcrumb('Blog', urlName: $paginator->isOnFirstPage() ? null : 'blog/post/index'),
);
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Blog</h1>
    <?php if ($currentUser->can(Permission::BlogManage)): ?>
        <div>
            <?= Html::a(
                NoEncode::string('<i class="bi bi-card-list me-1"></i> Manage'),
                $urlGenerator->generate('blog/manage/post/index'),
                ['class' => 'btn btn-outline-secondary btn-sm'],
            ) ?>
        </div>
    <?php endif; ?>
</div>
<?= CategoriesPanel::create($categories) ?>
<div class="mb-4">
    <?= PostsList::widget([$paginator]) ?>
</div>

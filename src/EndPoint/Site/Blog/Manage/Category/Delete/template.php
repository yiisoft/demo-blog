<?php

declare(strict_types=1);

use App\Blog\Domain\Category\Category;
use App\Shared\UrlGenerator;
use App\EndPoint\Site\Shared\Layout\Breadcrumbs\Breadcrumb;
use Yiisoft\Html\Html;
use Yiisoft\View\WebView;
use Yiisoft\Yii\View\Renderer\Csrf;

/**
 * @var WebView $this
 * @var UrlGenerator $urlGenerator
 * @var Csrf $csrf
 * @var Category $category
 */

$this->setTitle('Delete category "' . $category->name . '"');
$this->addToParameter(
    'breadcrumbs',
    new Breadcrumb('Blog', urlName: 'blog/post/index'),
    new Breadcrumb('Manage', urlName: 'blog/manage/post/index'),
    new Breadcrumb('Categories', urlName: 'blog/manage/category/index'),
    new Breadcrumb('Delete category'),
);
?>
<h1>Delete category "<?= Html::encode($category->name) ?>"?</h1>
<div class="container-fluid mt-4">
    <div class="border rounded p-3 bg-light mb-3">
        <strong>ID:</strong> <?= Html::encode($category->id) ?><br>
        <strong>Slug:</strong> <?= Html::encode($category->slug) ?>
    </div>
    <div class="alert alert-warning">
        <strong>Warning:</strong> Deleting this category will remove it from all posts that use it.
    </div>
    <div class="mt-4">
        <?= Html::form()
            ->post($urlGenerator->generate('blog/manage/category/delete', ['id' => $category->id]))
            ->csrf($csrf)
            ->open() ?>
        <?= Html::submitButton('Delete', ['class' => 'btn btn-danger']) ?>
        <?= Html::a('Cancel', $urlGenerator->generate('blog/manage/category/index'), ['class' => 'btn btn-outline-secondary']) ?>
        <?= '</form>' ?>
    </div>
</div>

<?php

declare(strict_types=1);

use App\EndPoint\Web\Blog\Manage\Category\Update\Form;
use App\Shared\UrlGenerator;
use App\EndPoint\Web\Shared\Layout\Breadcrumbs\Breadcrumb;
use Yiisoft\FormModel\FieldFactory;
use Yiisoft\Html\Html;
use Yiisoft\View\WebView;
use Yiisoft\Yii\View\Renderer\Csrf;

/**
 * @var WebView $this
 * @var UrlGenerator $urlGenerator
 * @var Csrf $csrf
 * @var Form $form
 */

$this->setTitle('Update category');
$this->addToParameter(
    'breadcrumbs',
    new Breadcrumb('Blog', urlName: 'blog/post/index'),
    new Breadcrumb('Manage', urlName: 'blog/manage/post/index'),
    new Breadcrumb('Categories', urlName: 'blog/manage/category/index'),
    new Breadcrumb('Update category'),
);

$field = new FieldFactory();
?>
<h1>Update Category</h1>
<div class="row mt-4">
    <div class="col-md-8">
        <?= $field->errorSummary($form)->onlyCommonErrors() ?>
        <?= Html::form()
            ->post($urlGenerator->categoryUpdate($form->category->id))
            ->csrf($csrf)
            ->open() ?>
        <?= $field->text($form, 'name') ?>
        <?= $field->textarea($form, 'description')->addInputAttributes(['rows' => 3]) ?>
        <?= $field->text($form, 'slug') ?>
        <?= $field->submitButton('Save')->afterInput(
            Html::a('Cancel', $urlGenerator->generate('blog/manage/category/index'), ['class' => 'btn btn-outline-secondary ms-2']),
        ) ?>
        <?= '</form>' ?>
    </div>
</div>

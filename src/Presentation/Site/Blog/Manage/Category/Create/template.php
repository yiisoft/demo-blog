<?php

declare(strict_types=1);

use App\Presentation\Site\Blog\Manage\Category\Create\Form;
use App\Presentation\Site\Shared\Layout\Breadcrumbs\Breadcrumb;
use App\Shared\UrlGenerator;
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

$this->setTitle('New category');
$this->addToParameter(
    'breadcrumbs',
    new Breadcrumb('Blog', urlName: 'blog/post/index'),
    new Breadcrumb('Manage', urlName: 'blog/manage/post/index'),
    new Breadcrumb('Categories', urlName: 'blog/manage/category/index'),
    new Breadcrumb('New category'),
);

$field = new FieldFactory();
?>
<h1>Create Category</h1>
<div class="row mt-4">
    <div class="col-md-8">
        <?= $field->errorSummary($form)->onlyCommonErrors() ?>
        <?= Html::form()
            ->post($urlGenerator->generate('blog/manage/category/create'))
            ->csrf($csrf)
            ->open() ?>
        <?= $field->text($form, 'name') ?>
        <?= $field->textarea($form, 'description')->addInputAttributes(['rows' => 3]) ?>
        <?= $field->text($form, 'slug') ?>
        <?= $field->submitButton('Create')->afterInput(
            Html::a('Cancel', $urlGenerator->generate('blog/manage/category/index'), ['class' => 'btn btn-outline-secondary ms-2']),
        ) ?>
        <?= '</form>' ?>
    </div>
</div>

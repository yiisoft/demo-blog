<?php

declare(strict_types=1);

use App\Blog\Domain\Post\PostStatus;
use App\EntryPoint\Web\Blog\Admin\Post\Update\Form;
use App\Shared\UrlGenerator;
use App\Web\Layout\Breadcrumbs\Breadcrumb;
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

$this->setTitle('Update post');
$this->addToParameter(
    'breadcrumbs',
    new Breadcrumb('Blog', $urlGenerator->generate('blog/post/index')),
    new Breadcrumb('Admin'),
    new Breadcrumb('Posts', $urlGenerator->generate('blog/admin/post/index')),
    new Breadcrumb('Update post'),
);

$field = new FieldFactory();
?>
<h1>Update Post</h1>
<div class="row mt-4">
    <div class="col-md-8">
        <?= $field->errorSummary($form)->onlyCommonErrors() ?>
        <?= Html::form()
            ->post($urlGenerator->generate('blog/admin/post/update', ['id' => $form->post->id]))
            ->csrf($csrf)
            ->open() ?>
        <?= $field->text($form, 'title') ?>
        <?= $field->textarea($form, 'body')->addInputAttributes(['rows' => 10]) ?>
        <?= $field->select($form, 'status')->optionsData(PostStatus::labelsByValue()) ?>
        <?= $field->date($form, 'publicationDate') ?>
        <?= $field->submitButton('Update Post')->afterInput(
            Html::a('Cancel', $urlGenerator->generate('blog/admin/post/index'), ['class' => 'btn btn-outline-secondary ms-2']),
        ) ?>
        <?= '</form>' ?>
    </div>
</div>
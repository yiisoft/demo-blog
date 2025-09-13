<?php

declare(strict_types=1);

use App\EntryPoint\Web\ChangePassword\Form;
use App\Shared\UrlGenerator;
use App\Web\Layout\Breadcrumbs\Breadcrumb;
use Yiisoft\FormModel\FieldFactory;
use Yiisoft\Html\Html;
use Yiisoft\View\WebView;
use Yiisoft\Yii\View\Renderer\Csrf;

/**
 * @var WebView $this
 * @var Csrf $csrf
 * @var UrlGenerator $urlGenerator
 * @var Form $form
 */

$this->setTitle('Password Change');
$this->addToParameter(
    'breadcrumbs',
    new Breadcrumb('Change my password'),
);

$field = new FieldFactory();
?>
<h1>Change my password</h1>
<div class="row mt-4">
    <div class="col-md-6">
        <?= $field->errorSummary($form)->onlyCommonErrors() ?>
        <?= Html::form()
            ->post($urlGenerator->changePassword())
            ->csrf($csrf)
            ->open() ?>
        <?= $field->password($form, 'current') ?>
        <?= $field->password($form, 'new') ?>
        <?= $field->password($form, 'new2') ?>
        <?= $field->submitButton('Save') ?>
        <?= '</form>' ?>
    </div>
</div>

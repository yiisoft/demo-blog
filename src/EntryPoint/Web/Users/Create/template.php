<?php

declare(strict_types=1);

use App\EntryPoint\Web\Users\Create\Form;
use App\Shared\UrlGenerator;
use App\Web\Access\Role;
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

$this->setTitle('New user');
$this->addToParameter(
    'breadcrumbs',
    new Breadcrumb('Users', $urlGenerator->generate('users/index')),
    new Breadcrumb('New user'),
);

$field = new FieldFactory();
?>
<h1>New User</h1>
<div class="row mt-4">
    <div class="col-md-6">
        <?= $field->errorSummary($form)->onlyCommonErrors() ?>
        <?= Html::form()
            ->post($urlGenerator->generate('users/create'))
            ->csrf($csrf)
            ->open() ?>
        <?= $field->text($form, 'login') ?>
        <?= $field->text($form, 'name') ?>
        <?= $field->password($form, 'password') ?>
        <?= $field->select($form, 'role')->optionsData(Role::labelsByValue())->prompt('— Select —') ?>
        <?= $field->submitButton('Create') ?>
        <?= '</form>' ?>
    </div>
</div>

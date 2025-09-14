<?php

declare(strict_types=1);

use App\EndPoint\Web\Users\ChangePassword\Form;
use App\EndPoint\Web\Users\ShortUserInfo;
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

$this->setTitle('Change User Password');
$this->addToParameter(
    'breadcrumbs',
    new Breadcrumb('Users', $urlGenerator->generate('users/index')),
    new Breadcrumb('Change password'),
);

$field = new FieldFactory();
?>
<h1>Change User Password</h1>
<div class="row mt-4">
    <div class="col-md-6">
        <?= ShortUserInfo::widget([$form->user]) ?>
        <?= $field->errorSummary($form)->onlyCommonErrors() ?>
        <?= Html::form()
            ->post($urlGenerator->generate('users/change-password', ['id' => $form->user->id]))
            ->csrf($csrf)
            ->open() ?>
        <?= $field->password($form, 'password') ?>
        <?= $field->submitButton('Change Password') ?>
        <?= '</form>' ?>
    </div>
</div>

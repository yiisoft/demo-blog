<?php

declare(strict_types=1);

use App\EndPoint\Web\User\ShortUserInfo;
use App\EndPoint\Web\User\Update\Form;
use App\Shared\UrlGenerator;
use App\User\Domain\UserStatus;
use App\EndPoint\Web\Shared\Access\Role;
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

$this->setTitle('Update user');
$this->addToParameter(
    'breadcrumbs',
    new Breadcrumb('Users', $urlGenerator->generate('user/index')),
    new Breadcrumb('Update user'),
);

$field = new FieldFactory();
?>
<h1>Update User</h1>
<div class="row mt-4">
    <div class="col-md-6">
        <?= ShortUserInfo::widget([$form->user]) ?>
        <?= $field->errorSummary($form)->onlyCommonErrors() ?>
        <?= Html::form()
            ->post($urlGenerator->generate('user/update', ['id' => $form->user->id]))
            ->csrf($csrf)
            ->open() ?>
        <?= $field->text($form, 'login') ?>
        <?= $field->text($form, 'name') ?>
        <?= $field->select($form, 'status')
            ->optionsData(UserStatus::labelsByValue())
            ->disabled($form->isCurrentUser)
?>
        <?= $field->select($form, 'role')
    ->optionsData(Role::labelsByValue())
    ->disabled($form->isCurrentUser)
?>
        <?= $field->submitButton('Save')->afterInput(
            Html::a('Cancel', $urlGenerator->generate('user/index'), ['class' => 'btn btn-outline-secondary ms-2']),
        ) ?>
        <?= '</form>' ?>
    </div>
</div>

<?php

declare(strict_types=1);

use App\EntryPoint\Web\Users\ShortUserInfo;
use App\Shared\UrlGenerator;
use App\User\Domain\User;
use App\Web\Layout\Breadcrumbs\Breadcrumb;
use Yiisoft\Html\Html;
use Yiisoft\View\WebView;
use Yiisoft\Yii\View\Renderer\Csrf;

/**
 * @var WebView $this
 * @var UrlGenerator $urlGenerator
 * @var Csrf $csrf
 * @var User $user
 */

$this->setTitle('Delete user "' . $user->login . '"');
$this->addToParameter(
    'breadcrumbs',
    new Breadcrumb('Users', $urlGenerator->generate('users/index')),
    new Breadcrumb('Delete user'),
);
?>
<h1>Delete user "<?= Html::encode($user->login) ?>"?</h1>
<div class="container-fluid mt-4">
    <?= ShortUserInfo::widget([$user]) ?>
    <?= Html::form()
        ->post($urlGenerator->generate('users/delete', ['id' => $user->id]))
        ->csrf($csrf)
        ->open() ?>
    <?= Html::submitButton('Delete', ['class' => 'btn btn-danger']) ?>
    <?= Html::a('Cancel', $urlGenerator->generate('users/index'), ['class' => 'btn btn-outline-secondary']) ?>
    <?= '</form>' ?>
</div>

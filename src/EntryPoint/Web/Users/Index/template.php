<?php

declare(strict_types=1);

use App\EntryPoint\Web\Users\Index\User;
use App\Shared\UrlGenerator;
use App\Web\Layout\Breadcrumbs\Breadcrumb;
use Yiisoft\Data\Db\QueryDataReader;
use Yiisoft\Html\Html;
use Yiisoft\View\WebView;
use Yiisoft\Yii\DataView\Column\ActionButton;
use Yiisoft\Yii\DataView\Column\ActionColumn;
use Yiisoft\Yii\DataView\Column\DataColumn;
use Yiisoft\Yii\DataView\GridView;

/**
 * @var WebView $this
 * @var UrlGenerator $urlGenerator
 * @var QueryDataReader<array-key,User> $dataReader
 */

$this->setTitle('Users');
$this->addToParameter('breadcrumbs', new Breadcrumb('Users'));
?>
<h1>Users</h1>
<p class="text-end">
    <?= Html::a(
        'Create user',
        $urlGenerator->generate('users/create'),
        ['class' => 'btn btn-outline-primary'],
    ) ?>
</p>
<?= GridView::widget()
    ->dataReader($dataReader)
    ->containerClass('mt-4')
    ->columns(
        new DataColumn(
            'id',
            header: 'ID',
            filter: true,
        ),
        new DataColumn(
            'login',
            filter: true,
        ),
        new ActionColumn(
            before: '<div class="btn-group">',
            after: '</div>',
            buttons: [
                new ActionButton(
                    Html::i()->class('bi bi-pencil'),
                    static fn(User $user) => $urlGenerator->generate('users/update', ['id' => $user->id]),
                    title: 'Update',
                ),
                new ActionButton(
                    Html::i()->class('bi bi-x-lg'),
                    static fn(User $user) => $urlGenerator->generate('users/delete', ['id' => $user->id]),
                    title: 'Delete',
                ),
            ],
        ),
    )
?>

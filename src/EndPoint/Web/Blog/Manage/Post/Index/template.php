<?php

declare(strict_types=1);

use App\Blog\Domain\Post\PostStatus;
use App\EndPoint\Web\Blog\Manage\Post\Index\DataReader\Post;
use App\EndPoint\Web\Blog\Manage\Post\Index\DataReader\PostDataReader;
use App\EndPoint\Web\Blog\Manage\Post\Index\TitleFilterFactory;
use App\EndPoint\Web\Blog\Manage\Post\PostStatusBadge;
use App\Shared\UrlGenerator;
use App\Web\Layout\Breadcrumbs\Breadcrumb;
use Yiisoft\Html\Html;
use Yiisoft\Html\NoEncode;
use Yiisoft\View\WebView;
use Yiisoft\Yii\DataView\Column\ActionButton;
use Yiisoft\Yii\DataView\Column\ActionColumn;
use Yiisoft\Yii\DataView\Column\DataColumn;
use Yiisoft\Yii\DataView\GridView;

/**
 * @var WebView $this
 * @var UrlGenerator $urlGenerator
 * @var PostDataReader $dataReader
 */

$this->setTitle('Posts');
$this->addToParameter(
    'breadcrumbs',
    new Breadcrumb('Blog', urlName: 'blog/post/index'),
    new Breadcrumb('Manage'),
);
?>
<div class="d-flex justify-content-between align-items-center">
    <h1>Posts</h1>
    <?= Html::a(
        NoEncode::string('<i class="bi bi-file-earmark-plus me-1"></i> Create post'),
        $urlGenerator->generate('blog/manage/post/create'),
        ['class' => 'btn btn-outline-primary btn-sm'],
    ) ?>
</div>
<?= GridView::widget()
    ->dataReader($dataReader)
    ->containerClass('mt-4')
    ->columns(
        new DataColumn(
            'title',
            header: 'Post',
            content: static function (Post $post): string {
                return Html::encode($post->title)
                    . '<br>'
                    . Html::small($post->id, ['class' => 'text-muted']);
            },
            encodeContent: false,
            filter: true,
            filterFactory: new TitleFilterFactory(),
        ),
        new DataColumn(
            'status',
            content: static fn(Post $post) => PostStatusBadge::widget([$post->status]),
            filter: PostStatus::labelsByValue(),
        ),
        new DataColumn(
            'publicationDate',
            header: 'Publication Date',
            content: static function (Post $post): string {
                return $post->publicationDate?->format('Y-m-d') ?? '<i class="text-muted">not set</i>';
            },
            encodeContent: false,
        ),
        new DataColumn(
            'createdBy',
            header: 'Created By',
            content: static function (Post $post): string {
                return Html::encode($post->createdBy->name)
                    . '<br>'
                    . Html::small($post->createdAt->format('Y-m-d H:m:i'), ['class' => 'text-muted']);
            },
            encodeContent: false,
        ),
        new DataColumn(
            'UpdatedBy',
            header: 'Updated By',
            content: static function (Post $post): string {
                return Html::encode($post->updatedBy->name)
                    . '<br>'
                    . Html::small($post->updatedAt->format('Y-m-d H:m:i'), ['class' => 'text-muted']);
            },
            encodeContent: false,
        ),
        new ActionColumn(
            before: '<div class="btn-group">',
            after: '</div>',
            buttons: [
                new ActionButton(
                    Html::i()->class('bi bi-pencil'),
                    static fn(Post $post) => $urlGenerator->generate('blog/manage/post/update', ['id' => $post->id]),
                    title: 'Update',
                ),
                new ActionButton(
                    Html::i()->class('bi bi-x-lg'),
                    static fn(Post $post) => $urlGenerator->generate('blog/manage/post/delete', ['id' => $post->id]),
                    title: 'Delete',
                ),
            ],
        ),
    )
?>

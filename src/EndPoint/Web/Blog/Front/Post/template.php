<?php

declare(strict_types=1);

use App\EndPoint\Web\Blog\Front\Shared\PostDataReader\Post;
use App\EndPoint\Web\Shared\Access\Permission;
use App\EndPoint\Web\Shared\Layout\Breadcrumbs\Breadcrumb;
use App\Shared\Formatter;
use App\Shared\UrlGenerator;
use Yiisoft\Html\Html;
use Yiisoft\Html\NoEncode;
use Yiisoft\User\CurrentUser;
use Yiisoft\View\WebView;

/**
 * @var WebView $this
 * @var UrlGenerator $urlGenerator
 * @var CurrentUser $currentUser
 * @var Formatter $formatter
 * @var Post $post
 */

$this->setTitle($post->title);
$this->addToParameter(
    'breadcrumbs',
    new Breadcrumb('Blog', $urlGenerator->blog()),
    new Breadcrumb((string) $post->title),
);
?>
<article>
    <header class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><?= Html::encode($post->title) ?></h1>
            <?php if ($currentUser->can(Permission::BlogManage)): ?>
                <div>
                    <?= Html::a(
                        NoEncode::string('<i class="bi bi-pencil me-1"></i> Edit'),
                        $urlGenerator->postUpdate($post->id),
                        ['class' => 'btn btn-outline-primary btn-sm'],
                    ) ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <small class="text-uppercase text-muted fst-italic">
                <?= $formatter->asLongDate($post->publicationDate) ?>
            </small>
        </div>
        <?php if ($post->categories !== []) { ?>
            <div class="mb-4">
                <?php foreach ($post->categories as $category) { ?>
                    <?= Html::a(
                        $category->name,
                        $urlGenerator->generate('blog/category/index', ['slug' => $category->slug]),
                        ['class' => 'badge fw-light bg-light text-dark border text-decoration-none link-primary link-opacity-75-hover me-2'],
                    ) ?>
                <?php } ?>
            </div>
        <?php } ?>
    </header>
    <?= nl2br(Html::encode($post->body)) ?>
</article>

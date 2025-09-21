<?php

declare(strict_types=1);

use App\EndPoint\Site\Blog\Front\Shared\PostDataReader\Post;
use App\Shared\Formatter;
use App\Shared\UrlGenerator;
use Yiisoft\Data\Paginator\OffsetPaginator;
use Yiisoft\Html\Html;
use Yiisoft\View\WebView;
use Yiisoft\Yii\DataView\Pagination\OffsetPagination;
use Yiisoft\Yii\DataView\Pagination\PaginationContext;

/**
 * @var WebView $this
 * @var Formatter $formatter
 * @var UrlGenerator $urlGenerator
 * @var OffsetPaginator $paginator
 * @var Post[] $posts
 */
?>

<?php foreach ($posts as $post) { ?>
    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-body">
            <h5 class="h5 mb-2">
                <?= Html::a($post->title, $urlGenerator->post($post->slug), [
                    'class' => 'text-dark text-decoration-underline link-primary link-opacity-100-hover',
                ]) ?>
            </h5>
            <div class="mb-2">
                <small class="text-uppercase text-muted fst-italic"><?= $formatter->asLongDate($post->publicationDate) ?></small>
            </div>
            <p class="card-text text-secondary">
                <?= Html::encode($post->description) ?>
            </p>
            <?php if ($post->categories !== []) { ?>
                <div class="mt-3">
                    <?php foreach ($post->categories as $category) { ?>
                        <?= Html::a($category->name, $urlGenerator->category($category->slug), [
                            'class' => 'badge fw-light bg-light text-dark border text-decoration-none link-primary link-opacity-75-hover',
                        ]) ?>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>
<?= OffsetPagination::widget()
    ->withContext(
        new PaginationContext(
            $urlGenerator->blog(PaginationContext::URL_PLACEHOLDER),
            $urlGenerator->blog(PaginationContext::URL_PLACEHOLDER),
            $urlGenerator->blog(),
        ),
    )
    ->withPaginator($paginator)
?>

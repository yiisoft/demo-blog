<?php

declare(strict_types=1);

use App\Shared\UrlGenerator;
use App\Web\Layout\Breadcrumbs\Breadcrumb;
use Yiisoft\Html\Html;
use Yiisoft\Html\NoEncode;
use Yiisoft\User\CurrentUser;
use Yiisoft\View\WebView;

/**
 * @var WebView $this
 * @var UrlGenerator $urlGenerator
 * @var CurrentUser $currentUser
 */

$this->setTitle('Blog');
$this->addToParameter('breadcrumbs', new Breadcrumb('Blog'));
?>
<div class="d-flex justify-content-between align-items-center">
    <h1>Blog</h1>
    <?php if (!$currentUser->isGuest()): ?>
        <div>
            <?= Html::a(
                NoEncode::string('<i class="bi bi-gear me-1"></i> Admin'),
                $urlGenerator->generate('blog/admin/post/index'),
                ['class' => 'btn btn-outline-secondary btn-sm'],
            ) ?>
        </div>
    <?php endif; ?>
</div>
<div class="mt-4">
    <p class="text-muted">No posts published yet.</p>
</div>

<?php

declare(strict_types=1);

use App\Shared\UrlGenerator;
use Yiisoft\Html\Html;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\View\WebView;

/**
 * @var WebView $this
 * @var UrlGenerator $urlGenerator
 * @var CurrentRoute $currentRoute
 * @var string $title
 * @var string $message
 */

$this->setTitle('404');
?>
<div class="text-center">
    <h1>
        <?= Html::encode($title) ?>
    </h1>
    <?php
    if ($message !== '') {
        echo Html::p($message);
    }
?>
    <p>
        <a href="<?= $urlGenerator->home() ?>">Go Back Home</a>
    </p>
</div>

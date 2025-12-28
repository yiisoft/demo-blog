<?php

declare(strict_types=1);

use App\Presentation\Api;
use Yiisoft\Router\Route;

return [
    Route::get('/blog/category/list')->action(Api\Blog\Category\List\Action::class),
    Route::get('/blog/post/list')->action(Api\Blog\Post\List\Action::class),
];

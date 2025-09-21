<?php

declare(strict_types=1);

use App\EndPoint\Api;
use Yiisoft\Router\Route;

return [
    Route::get('/blog/category/list')->action(Api\Blog\Category\List\Action::class),
];

<?php

declare(strict_types=1);

use App\EndPoint\Site;
use App\EndPoint\Site\Shared\Access\CheckAccess;
use App\EndPoint\Site\Shared\Access\Permission;
use Yiisoft\Auth\Middleware\Authentication;
use Yiisoft\Router\Group;
use Yiisoft\Router\Route;

return [
    Route::get('/')->action(Site\Home\Action::class)->name('home'),

    /**
     * Blog
     */
    Group::create()
        ->middleware(CheckAccess::definition(Permission::BlogManage))
        ->routes(
            Route::get('/blog/manage')->action(Site\Blog\Manage\Post\Index\Action::class)->name('blog/manage/post/index'),
            Route::methods(['GET', 'POST'], '/blog/manage/post/create')->action(Site\Blog\Manage\Post\Create\Action::class)->name('blog/manage/post/create'),
            Route::methods(['GET', 'POST'], '/blog/manage/post/update/{id}')->action(Site\Blog\Manage\Post\Update\Action::class)->name('blog/manage/post/update'),
            Route::methods(['GET', 'POST'], '/blog/manage/post/delete/{id}')->action(Site\Blog\Manage\Post\Delete\Action::class)->name('blog/manage/post/delete'),
            Route::get('/blog/manage/categories')->action(Site\Blog\Manage\Category\Index\Action::class)->name('blog/manage/category/index'),
            Route::methods(['GET', 'POST'], '/blog/manage/category/create')->action(Site\Blog\Manage\Category\Create\Action::class)->name('blog/manage/category/create'),
            Route::methods(['GET', 'POST'], '/blog/manage/category/update/{id}')->action(Site\Blog\Manage\Category\Update\Action::class)->name('blog/manage/category/update'),
            Route::methods(['GET', 'POST'], '/blog/manage/category/delete/{id}')->action(Site\Blog\Manage\Category\Delete\Action::class)->name('blog/manage/category/delete'),
        ),
    Route::get('/blog/category/{slug}[/{page}]')->action(Site\Blog\Front\Category\Action::class)->name('blog/category/index'),
    Route::get('/blog/post/{slug}')->action(Site\Blog\Front\Post\Action::class)->name('blog/post/view'),
    Route::get('/blog[/{page}]')->action(Site\Blog\Front\Index\Action::class)->name('blog/post/index'),

    /**
     * Users
     */
    Group::create()
        ->middleware(CheckAccess::definition(Permission::UserManage))
        ->routes(
            Route::get('/users')->action(Site\User\Index\Action::class)->name('user/index'),
            Route::methods(['GET', 'POST'], '/users/create')
                ->action(Site\User\Create\Action::class)
                ->name('user/create'),
            Route::methods(['GET', 'POST'], '/users/update/{id}')
                ->action(Site\User\Update\Action::class)
                ->name('user/update'),
            Route::methods(['GET', 'POST'], '/users/change-password/{id}')
                ->action(Site\User\ChangePassword\Action::class)
                ->name('user/change-password'),
            Route::methods(['GET', 'POST'], '/users/delete/{id}')
                ->action(Site\User\Delete\Action::class)
                ->name('user/delete'),
        ),

    /**
     * Current user
     */
    Route::methods(['GET', 'POST'], '/login')->action(Site\Login\Action::class)->name('login'),
    Group::create()
        ->middleware(Authentication::class)
        ->routes(
            Route::post('/logout')->action(Site\Logout\Action::class)->name('logout'),
            Route::methods(['GET', 'POST'], '/profile/change-password')
                ->action(Site\Profile\ChangePassword\Action::class)
                ->name('profile/change-password'),
            Route::methods(['GET', 'POST'], '/profile/update')
                ->action(Site\Profile\UpdateProfile\Action::class)
                ->name('profile/update'),
        ),
];

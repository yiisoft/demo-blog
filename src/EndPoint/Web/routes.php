<?php

declare(strict_types=1);

use App\EndPoint\Web;
use App\Web\Access\CheckAccess;
use App\Web\Access\Permission;
use Yiisoft\Auth\Middleware\Authentication;
use Yiisoft\Router\Group;
use Yiisoft\Router\Route;

return [
    Route::get('/')->action(Web\Home\Action::class)->name('home'),

    /**
     * Blog
     */
    Route::get('/blog')->action(Web\Blog\Post\Index\Action::class)->name('blog/post/index'),
    Group::create()
        ->middleware(CheckAccess::definition(Permission::BlogManage))
        ->routes(
            Route::get('/blog/manage')->action(Web\Blog\Manage\Post\Index\Action::class)->name('blog/manage/post/index'),
            Route::methods(['GET', 'POST'], '/blog/manage/post/create')->action(Web\Blog\Manage\Post\Create\Action::class)->name('blog/manage/post/create'),
            Route::methods(['GET', 'POST'], '/blog/manage/post/update/{id}')->action(Web\Blog\Manage\Post\Update\Action::class)->name('blog/manage/post/update'),
            Route::methods(['GET', 'POST'], '/blog/manage/post/delete/{id}')->action(Web\Blog\Manage\Post\Delete\Action::class)->name('blog/manage/post/delete'),
        ),

    /**
     * Users
     */
    Group::create()
        ->middleware(CheckAccess::definition(Permission::UserManage))
        ->routes(
            Route::get('/users')->action(Web\User\Index\Action::class)->name('user/index'),
            Route::methods(['GET', 'POST'], '/users/create')
                ->action(Web\User\Create\Action::class)
                ->name('user/create'),
            Route::methods(['GET', 'POST'], '/users/update/{id}')
                ->action(Web\User\Update\Action::class)
                ->name('user/update'),
            Route::methods(['GET', 'POST'], '/users/change-password/{id}')
                ->action(Web\User\ChangePassword\Action::class)
                ->name('user/change-password'),
            Route::methods(['GET', 'POST'], '/users/delete/{id}')
                ->action(Web\User\Delete\Action::class)
                ->name('user/delete'),
        ),

    /**
     * Current user
     */
    Route::methods(['GET', 'POST'], '/login')->action(Web\Login\Action::class)->name('login'),
    Group::create()
        ->middleware(Authentication::class)
        ->routes(
            Route::post('/logout')->action(Web\Logout\Action::class)->name('logout'),
            Route::methods(['GET', 'POST'], '/profile/change-password')
                ->action(Web\Profile\ChangePassword\Action::class)
                ->name('profile/change-password'),
            Route::methods(['GET', 'POST'], '/profile/update')
                ->action(Web\Profile\UpdateProfile\Action::class)
                ->name('profile/update'),
        ),
];

<?php

declare(strict_types=1);

use App\EntryPoint\Web;
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
            Route::get('/blog/admin')->action(Web\Blog\Admin\Post\Index\Action::class)->name('blog/admin/post/index'),
            Route::methods(['GET', 'POST'], '/blog/admin/post/create')->action(Web\Blog\Admin\Post\Create\Action::class)->name('blog/admin/post/create'),
            Route::methods(['GET', 'POST'], '/blog/admin/post/update/{id}')->action(Web\Blog\Admin\Post\Update\Action::class)->name('blog/admin/post/update'),
            Route::methods(['GET', 'POST'], '/blog/admin/post/delete/{id}')->action(Web\Blog\Admin\Post\Delete\Action::class)->name('blog/admin/post/delete'),
        ),

    /**
     * Users
     */
    Group::create()
        ->middleware(CheckAccess::definition(Permission::UsersManage))
        ->routes(
            Route::get('/users')->action(Web\Users\Index\Action::class)->name('users/index'),
            Route::methods(['GET', 'POST'], '/users/create')
                ->action(Web\Users\Create\Action::class)
                ->name('users/create'),
            Route::methods(['GET', 'POST'], '/users/update/{id}')
                ->action(Web\Users\Update\Action::class)
                ->name('users/update'),
            Route::methods(['GET', 'POST'], '/users/change-password/{id}')
                ->action(Web\Users\ChangePassword\Action::class)
                ->name('users/change-password'),
            Route::methods(['GET', 'POST'], '/users/delete/{id}')
                ->action(Web\Users\Delete\Action::class)
                ->name('users/delete'),
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

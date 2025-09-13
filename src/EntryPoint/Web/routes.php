<?php

declare(strict_types=1);

use App\EntryPoint\Web;
use Yiisoft\Auth\Middleware\Authentication;
use Yiisoft\Router\Group;
use Yiisoft\Router\Route;

return [
    Route::get('/')->action(Web\Home\Action::class)->name('home'),
    Route::methods(['GET', 'POST'], '/login')->action(Web\Login\Action::class)->name('login'),

    Group::create()
        ->middleware(Authentication::class)
        ->routes(
            Route::post('/logout')->action(Web\Logout\Action::class)->name('logout'),

            /**
             * Profile
             */
            Route::methods(['GET', 'POST'], '/profile/change-password')
                ->action(Web\Profile\ChangePassword\Action::class)
                ->name('profile/change-password'),
            Route::methods(['GET', 'POST'], '/profile/update')
                ->action(Web\Profile\UpdateProfile\Action::class)
                ->name('profile/update'),

            /**
             * Users
             */
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
];

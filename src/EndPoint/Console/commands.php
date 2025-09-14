<?php

declare(strict_types=1);

use App\EndPoint\Console;

return [
    'user:create-admin' => Console\CreateAdmin\Action::class,
];

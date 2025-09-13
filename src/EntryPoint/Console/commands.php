<?php

declare(strict_types=1);

use App\EntryPoint\Console;

return [
    'user:create-admin' => Console\CreateAdmin\Action::class,
];

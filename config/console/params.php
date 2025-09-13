<?php

declare(strict_types=1);

return [
    'yiisoft/yii-console' => [
        'commands' => require dirname(__DIR__, 2) . '/src/EntryPoint/Console/commands.php',
    ],
];

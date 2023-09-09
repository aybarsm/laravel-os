<?php

return [
    'concretes' => [
        'family' => [
            'linux' => env('OS_CONCRETE_LINUX', \Aybarsm\Laravel\Os\Concretes\OsLinux::class),
            'darwin' => env('OS_CONCRETE_DARWIN', \Aybarsm\Laravel\Os\Concretes\OsDarwin::class),
        ],
    ],
];

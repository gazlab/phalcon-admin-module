<?php

return [
    'application' => [
        'controllersDir' => __DIR__ . '/../controllers/',
        'modelsDir' => __DIR__ . '/../models/',
        'viewsDir' => __DIR__ . '/../views/',
    ],

    // Gazlab Config
    'adminResources' => [
        'modules' => [
            'index', 'create', 'update', 'delete',
        ],
        'resources' => [
            'index', 'create', 'update', 'delete',
        ],
        'profiles' => [
            'index', 'create', 'update', 'delete',
        ],
        'permission' => [
            'index', 'create', 'update', 'delete',
        ],
        'users' => [
            'index', 'create', 'update', 'delete',
        ],
        'session' => [
            'profile',
        ],
    ],
];

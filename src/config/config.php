<?php

return [
    'application' => [
        'controllersDir' => __DIR__ . '/../controllers/',
        'modelsDir' => __DIR__ . '/../models/',
        'viewsDir' => __DIR__ . '/../views/',
    ],
    'gazlab' => [
        'title' => 'Gazlab Admin',
        'logo' => [
            'lg' => '<b>Gazlab</b>Admin',
            'sm' => '<b>GZ</b>A',
        ],
    ],
    'privateResources' => [
        'profiles' => [
            'index',
            'create',
            'update',
            'delete',
        ],
        'users' => [
            'index',
            'create',
            'update',
            'delete',
            'profile',
        ],
        'permissions' => [
            'index',
            'create',
            'update',
            'delete',
        ],
        // 'session' => [
        //     'signOut',
        // ],
        // 'index' => [
        //     'index',
        // ],
    ],
];

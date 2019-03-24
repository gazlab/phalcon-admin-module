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
        'footer' => '<strong>Copyright &copy; 2019 <a href="#">Gazlab</a>.</strong> All rights reserved.',
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

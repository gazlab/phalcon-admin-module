<?php

use Phalcon\Config;
use Phalcon\Logger;

return new Config([
    'privateResources' => [
        'users' => [
            'index',
            'create',
            'update',
            'delete',
            'history',
            'profile',
            'profileHistory'
        ],
        'profiles' => [
            'index',
            'create',
            'update',
            'delete',
            'history'
        ],
        // 'permissions' => [
        //     'index'
        // ],
        // 'session' => [
        //     'profile'
        // ],
    ]
]);
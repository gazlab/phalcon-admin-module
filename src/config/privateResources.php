<?php

use Phalcon\Config;

return new Config([
    'privateResources' => [
        'users' => [
            //     'index',
            //     'search',
            //     'edit',
            //     'create',
            //     'delete',
            //     'changePassword'
            'profile',
        ],
        // 'profiles' => [
        //     'index',
        //     'search',
        //     'edit',
        //     'create',
        //     'delete'
        // ],
        // 'permissions' => [
        //     'index'
        // ],
        // 'session' => [
        //     'signIn',
        //     'signOut'
        // ]
    ]
]);

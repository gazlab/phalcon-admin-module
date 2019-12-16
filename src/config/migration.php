<?php

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/../db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/../db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'development',
        'production' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'production_db',
            'user' => 'root',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8',
            'table_prefix'=> 'ga_'
        ],
        'development' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'akuntansi_development',
            'user' => 'root',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8',
            'table_prefix'=> 'ga_'
        ],
        'testing' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'testing_db',
            'user' => 'root',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8',
            'table_prefix'=> 'ga_'
        ]
    ],
    'version_order' => 'creation'
];
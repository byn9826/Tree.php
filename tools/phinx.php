<?php
require(dirname(__dir__) . '/app/Config.php');

return [
    "paths" => [
        "migrations" => "./app/migrations"
    ],
    "environments" => [
        "default_migration_table" => "phinxlog",
        "default_database" => "dev",
        "dev" => [
            "adapter" => "mysql",
            "host" => \Tree\Info\Config::$mysql['host'],
            "name" => \Tree\Info\Config::$mysql['database'],
            "user" => \Tree\Info\Config::$mysql['username'],
            "pass" => \Tree\Info\Config::$mysql['password'],
            "port" => \Tree\Info\Config::$mysql['port'],
        ]
    ]
];

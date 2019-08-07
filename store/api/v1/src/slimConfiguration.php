<?php

namespace src;

function slimConfiguration(): \Slim\Container
{
    $configuration = [
        'settings' => [
            'displayErrorDetails' => true,
            "db_main" => [
                "host" => "localhost",
                "dbname" => "marketplace",
                "user" => "root",
                "pass" => ""
            ],
            "db_log" => [
                "host" => "localhost",
                "dbname" => "marketplace_log",
                "user" => "root",
                "pass" => ""
            ]
        ]
    ];
    return new \Slim\Container($configuration);
}
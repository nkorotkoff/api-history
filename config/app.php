<?php

use Leaf\Config;

$init = function () {

    function _getEnv(string $value): ?string
    {
        return $_ENV[$value];
    }

    (function ()
    {
        $dotenvPath = __DIR__ . '/../.env';

        if (file_exists($dotenvPath)) {
            $dotenv = Dotenv\Dotenv::createImmutable(dirname($dotenvPath));
            $dotenv->load();
        }
    })();


    (function ()
    {
        $db = [
            'driver' => 'pdo_mysql',
            'dbname' => _getEnv('MYSQL_DATABASE'),
            'host' => _getEnv('MYSQL_HOST'),
            'port' => _getEnv('MYSQL_PORT'),
            'user' => 'root',
            'password' => _getEnv('MYSQL_ROOT_PASSWORD'),
            'charset' => 'utf8',
        ];

        $secretKey = _getEnv('SECRET_KEY');

        Config::set('secret_key', $secretKey);
        Config::set('db', $db);
    })();

};

$init();

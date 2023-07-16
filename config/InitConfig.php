<?php

use Dotenv\Dotenv;
use Leaf\Config;


class InitConfig
{
    public function __construct()
    {
        $this->init();
    }

    private function init()
    {
        $this->setEnv();

        $this->setConfig();
    }

    private function setEnv()
    {
        $dotenvPath = __DIR__ . '/../.env';

        if (file_exists($dotenvPath)) {
            $dotenv = Dotenv::createImmutable(dirname($dotenvPath));
            $dotenv->load();
        }
    }

    private function setConfig()
    {
        $getEnv = static function (string $value): ?string {
            return $_ENV[$value] ?? null;
        };

        $db = [
            'driver' => 'pdo_mysql',
            'dbname' => $getEnv('MYSQL_DATABASE'),
            'host' => $getEnv('MYSQL_HOST'),
            'port' => $getEnv('MYSQL_PORT'),
            'user' => 'root',
            'password' => $getEnv('MYSQL_ROOT_PASSWORD'),
            'charset' => 'utf8',
        ];

        $secretKey = $getEnv('SECRET_KEY');

        app()->config('secret_key', $secretKey);
        app()->config('db', $db);
        app()->config('file_log', __DIR__ . '/../logs/logs.txt');
    }
}

new InitConfig();


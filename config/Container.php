<?php


use app\components\logger\LoggerComponent;
use app\services\AuthService\AuthService;

$builder = new \DI\ContainerBuilder();

$builder->useAutowiring(true)
    ->useAutowiring(true);
$builder->addDefinitions([
    'logger' => new LoggerComponent()
]);
$container = $builder->build();



app()->config('container', $container);
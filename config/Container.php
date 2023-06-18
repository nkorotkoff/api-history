<?php


use app\services\AuthService\AuthService;

$builder = new \DI\ContainerBuilder();

$builder->useAutowiring(true)
    ->useAutowiring(true);
$container = $builder->build();


app()->config('container', $container);
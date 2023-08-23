<?php


use app\components\logger\LoggerComponent;
use Symfony\Component\Cache\Adapter\FilesystemTagAwareAdapter;

$builder = new \DI\ContainerBuilder();

$builder->useAutowiring(true)
    ->useAutowiring(true);
$builder->addDefinitions([
    'logger' => new LoggerComponent(),
    'cache' => new FilesystemTagAwareAdapter()
]);
$container = $builder->build();



app()->config('container', $container);
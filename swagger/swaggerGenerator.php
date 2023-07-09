<?php

require __DIR__ . '/../vendor/autoload.php';

$path = __DIR__;
$openapi = \OpenApi\Generator::scan([$path]);

$file = __DIR__ . '/openapi.yaml';
file_put_contents($file, $openapi->toYaml());
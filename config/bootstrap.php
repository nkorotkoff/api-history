<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/InitConfig.php';

$paths = array(__DIR__."/../app/entities");

$isDevMode = app()->config('mode') === 'development';

$dbParams = app()->config('db');

$config = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);

$connection = DriverManager::getConnection($dbParams);

app()->config('connection', $connection);

$entityManager = new EntityManager($connection, $config);

app()->config('entityManager', $entityManager);
<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

$paths = array(__DIR__."/../app/entity");
$isDevMode = app()->config('mode') === 'development';

$dbParams = app()->config('db');

$config = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);

$connection = DriverManager::getConnection($dbParams);

app()->config('connection', $connection);

$entityManager = new EntityManager($connection, $config);
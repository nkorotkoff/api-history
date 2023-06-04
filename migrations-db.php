<?php

use Doctrine\DBAL\DriverManager;
use Leaf\Config;

require_once "./config/app.php";

return DriverManager::getConnection(Config::get('db'));
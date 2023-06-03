<?php

use app\controllers\MainController;

$app = app();

$app->get('/', MainController::class.'@index');

$app->run();
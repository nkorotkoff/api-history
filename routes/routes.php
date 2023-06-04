<?php

use app\controllers\MainController;
use app\middlewares\AccessMiddleware;

$app = app();

$app->hook('router.before.dispatch', function () use($app) {
       $accessMiddleware = new AccessMiddleware(\Leaf\Http\Headers::get('X-Encrypted-Key'));
       $app->use($accessMiddleware);
});

$app->get('/', MainController::class.'@index');


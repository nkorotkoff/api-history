<?php

use app\controllers\AuthController;
use app\middlewares\AccessMiddleware;

$app = app();

$app->hook('router.before.dispatch', function () use($app) {
       $accessMiddleware = new AccessMiddleware(\Leaf\Http\Headers::get('X-Encrypted-Key'));
       $app->use($accessMiddleware);
});



$app->group('/api', function () use($app) {
    $app->post('/register', AuthController::class.'@register');
});
<?php

use app\components\logger\LoggerComponent;
use app\controllers\AuthController;
use app\middlewares\AccessMiddleware;
use app\services\AuthService\JwtService;
use Leaf\Http\Headers;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

$app = app();

$app->registerMiddleware('auth', function () {
    $accessMiddleware = new AccessMiddleware(Headers::get('Access-Token'), Headers::get('Refresh-Token'));
    $accessMiddleware->call();
});

$app->hook('router.before.dispatch', function () use ($app) {
    $app->config('container')->get('logger')->log(Logger::INFO, 'URL:' . $_SERVER['REQUEST_URI'] . ' ' . 'Request:' . json_encode($_REQUEST));
});



$app->group('/api', function () use($app) {
    $app->post('/register', AuthController::class.'@register');
    $app->post('/login', AuthController::class.'@login');
});

$app->group('/api',['middleware' => 'auth', function () use($app) {

}]);


if ($app->config('mode') === 'development') {
    $app->get('/swagger', function () use ($app) {
        $app->response()->page(__DIR__ . '/../swagger/swaggerUi.php' );
    });

    $app->get('/swagger-yaml', function () use ($app) {
        $app->response()->page( __DIR__ . '/../swagger/openapi.yaml' );
    });
}
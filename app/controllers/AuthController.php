<?php

namespace app\controllers;

use app\dto\Auth\RegisterDto;
use app\entities\User;
use app\repositories\AuthRepository\AuthRepository;
use app\Requests\ErrorRequest;
use app\services\AuthService\AuthService;
use Doctrine\ORM\EntityManager;

class AuthController extends Controller
{

    private AuthService $authService;

    public function __construct()
    {
        parent::__construct();
        $container = app()->config('container');
        $this->authService = $container->get(AuthService::class);
    }


    public function register()
    {
        $registerData = new RegisterDto($this->request->body());
        if ($registerData->error) {
            $this->response->json(ErrorRequest::setError($registerData->error), 403);
        }
        $this->response->json($this->authService->register($registerData));
    }
}
<?php

namespace app\controllers;

use app\dto\Auth\LoginDto;
use app\dto\Auth\RegisterDto;
use app\Requests\ErrorRequest;
use app\Requests\ResponseCodes;
use app\Requests\SuccessResponse;
use app\services\AuthService\AuthService;
use app\services\AuthService\JwtService;
use Psr\Container\ContainerInterface;

class AuthController extends Controller
{

    private AuthService $authService;
    private JwtService $jwtService;

    public function __construct()
    {
        parent::__construct();
        /** @var ContainerInterface $container */
        $container = app()->config('container');
        $this->authService = $container->get(AuthService::class);
        $this->jwtService = $container->get(JwtService::class);
    }


    public function register()
    {
        $registerData = new RegisterDto($this->request->body());
        if ($registerData->hasError()) {
            $this->response->json(ErrorRequest::setErrorException($registerData->error), 403);
        }

        $response = $this->authService->register($registerData);
        if ($response['code'] === ResponseCodes::OK) {
           $this->jwtService->setAccessAndRefreshTokens($response['result']);
        }
        $this->response->json($response);
    }

    public function login()
    {
        $loginData = new LoginDto($this->request->body());
        if ($loginData->hasError()) {
            $this->response->json(ErrorRequest::setErrorException($loginData->error), 400);
        }
        $isUserCredentialsCorrect  = $this->authService->login($loginData);
        if ($isUserCredentialsCorrect) {
            $this->jwtService->setAccessAndRefreshTokens($isUserCredentialsCorrect);
            $this->response->json(SuccessResponse::setData(ResponseCodes::OK, $isUserCredentialsCorrect));
        }
        $this->response->json(ErrorRequest::setErrorWithCode(ResponseCodes::WRONG_USER_CREDENTIALS), 400);
    }

}
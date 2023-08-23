<?php


namespace app\middlewares;


use app\entities\UserAuthEntity;
use app\repositories\AuthRepository\AuthRepository;
use app\Requests\ErrorRequest;
use app\Requests\ResponseCodes;
use app\services\AuthService\AuthService;
use app\services\AuthService\JwtService;
use Exception;
use Leaf\App;
use Leaf\Config;
use Leaf\Http\Headers;
use Leaf\Middleware;
use Psr\Container\ContainerInterface;

class AccessMiddleware extends Middleware
{

    const PRODUCTION = 'production';

    protected ?string $accessToken;
    protected ?string $refreshToken;
    protected JwtService $jwtService;
    protected AuthRepository $authRepository;

    public function __construct(?string $accessToken, ?string $refreshToken)
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->jwtService = app()->config('container')->get(JwtService::class);;
        $this->authRepository = app()->config('container')->get(AuthRepository::class);
        $this->app = app();
    }


    public function call()
    {
        $this->isHeadersProvided();

        $verifiedAccessToken = $this->jwtService->verifyToken($this->accessToken);

        $verifiedRefreshToken = $this->jwtService->verifyToken($this->refreshToken);
        if (isset($verifiedAccessToken['success']) && $verifiedAccessToken['success']) {
            $this->setUser($verifiedAccessToken['data']->userId);
            return $this->next();
        } elseif (isset($verifiedRefreshToken['success']) && $verifiedRefreshToken['success']) {
            if ($this->authRepository->getUserByRefreshToken($this->refreshToken)) {
                $this->jwtService->setAccessAndRefreshTokens($verifiedRefreshToken['data']->userId);
                return $this->next();
            }
            $this->returnNotAuthorize();
        }  else {
            $this->returnNotAuthorize();
        }
    }

    private function isHeadersProvided(): void
    {
        if (empty($this->accessToken) || empty($this->refreshToken)) {
            $this->returnNotAuthorize();
        }
    }

    public function returnNotAuthorize()
    {
        $this->app->response()->json(ErrorRequest::setErrorWithCode(ResponseCodes::USER_TOKENS_WRONG), 403);
        return $this->next();
    }

    private function setUser($userId)
    {
        $user = $this->authRepository->getUserById($userId);
        if (empty($user)) {
            $this->returnNotAuthorize();
        }
        (UserAuthEntity::getInstance())->setUser($user);
    }

}
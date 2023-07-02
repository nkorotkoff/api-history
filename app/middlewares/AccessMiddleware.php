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

    private ?string $accessToken;
    private ?string $refreshToken;
    private JwtService $jwtService;

    public function __construct(?string $accessToken, ?string $refreshToken, JwtService $jwtService)
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->jwtService = $jwtService;
        $this->app = app();
        $this->isHeadersProvided();
    }



    public function call()
    {
        $verifiedAccessToken = $this->jwtService->verifyToken($this->accessToken);

        $verifiedRefreshToken = $this->jwtService->verifyToken($this->refreshToken);
        if ($verifiedAccessToken['success']) {
            $this->setUser($verifiedAccessToken['data']->userId);
            return $this->next();
        } elseif ($verifiedRefreshToken['success']) {
            if ((new AuthRepository())->getUserByRefreshToken($this->refreshToken)) {
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
        if (!is_string($this->accessToken) || !is_string($this->refreshToken)) {
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
        $user = (new AuthRepository())->getUserById($userId);
        if (!$user) {
            $this->returnNotAuthorize();
        }
        (UserAuthEntity::getInstance())->setUser($user);
    }

}
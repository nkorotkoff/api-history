<?php


namespace tests\auth;


use app\middlewares\AccessMiddleware;

class AccessMiddlewareTest extends AccessMiddleware
{

    public function __construct(?string $accessToken, ?string $refreshToken, $authRepository)
    {
        parent::__construct($accessToken, $refreshToken);

        $this->authRepository = $authRepository;

    }

    const NOT_AUTHORIZED = 'not authorized';

    public function returnNotAuthorize(): string
    {
        throw new \Exception(self::NOT_AUTHORIZED);
    }
}
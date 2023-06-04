<?php


namespace app\middlewares;


use Leaf\App;
use Leaf\Config;
use Leaf\Middleware;

class AccessMiddleware extends Middleware
{

    const PRODUCTION = 'production';

    private ?string $xEncryptedKey;

    public function __construct(?string $xEncryptedKey)
    {
        $this->xEncryptedKey = $xEncryptedKey;
        $this->app = app();
        $this->isEncryptedKeyExist();
    }



    public function call()
    {
        $key = openssl_decrypt(base64_decode($this->xEncryptedKey), 'AES-256-CBC', Config::get('secretKey'));

        if ($key !== false) {
            return $this->next();
        } else {
            $this->returnNotAuthorize();
        }
    }

    public function isEncryptedKeyExist(): void
    {
        if (!is_string($this->xEncryptedKey)) {
            $this->returnNotAuthorize();
        }
    }

    public function returnNotAuthorize()
    {
        $currentMode = $this->app->config('mode');
        if ($currentMode === self::PRODUCTION ) {
            $this->app->response()->json('not authorized', 403, true);
        }
        return $this->next();
    }



}
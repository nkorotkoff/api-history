<?php


namespace app\services\AuthService;


use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Leaf\Http\Headers;

class JwtService
{

    private string $secret;

    public function __construct()
    {
        $this->secret = app()->config('secret_key');
    }

    public function generateRefreshToken(int $userId)
    {

        $expirationTime = time() + (30 * 24 * 60 * 60);

        $payload = [
            'exp' => $expirationTime,
            'userId' => $userId,
        ];

        $refreshToken = JWT::encode($payload, $this->secret, 'HS256');

        try {
            /** @var AuthService $authService */
            $authService = app()->config('container')->get(AuthService::class);
            $authService->saveUserRefreshToken($userId, $refreshToken);
        } catch (\Exception $exception) {
            /** TODO ADD LOG */
        }

        return $refreshToken;
    }

    public function generateAccessToken(int $userId)
    {
        $expirationTime = time() + 60*60*24;

        $payload = [
            'exp' => $expirationTime,
            'userId' => $userId,
        ];

        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function verifyToken($token): array
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secret, 'HS256'));
            return ['success' => true, 'data' => $decoded];
        } catch (\Firebase\JWT\ExpiredException $e) {
            return ['success' => false, 'error' => 'Token expired'];
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            return ['success' => false, 'error' => 'Invalid token signature'];
        } catch (\Firebase\JWT\BeforeValidException $e) {
            return ['success' => false, 'error' => 'Token not yet valid'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Error while decoding token'];
        }
    }

    public function setAccessAndRefreshTokens(int $userId)
    {
        Headers::set([
            'access-token' => $this->generateAccessToken($userId),
            'refresh-token' => $this->generateRefreshToken($userId)
        ]);
    }
}
<?php


namespace app\services\AuthService;


use app\dto\Auth\LoginDto;
use app\dto\Auth\RegisterDto;
use app\entities\User;
use app\entities\UserAuthEntity;
use app\repositories\AuthRepository\AuthRepository;
use app\Requests\ErrorRequest;
use app\Requests\ResponseCodes;
use app\Requests\SuccessResponse;
use Doctrine\ORM\EntityManager;

class AuthService
{
    private AuthRepository $authRepository;
    private EntityManager $entityManager;

    public function __construct(AuthRepository $authRepository)
    {
        $this->entityManager = app()->config('entityManager');
        $this->authRepository = $authRepository;
    }

    public function register(RegisterDto $registerDto): array
    {
        if ($this->authRepository->getUser($registerDto)) {
            return ErrorRequest::setErrorWithCode(ResponseCodes::USER_ALREADY_EXISTS);
        }

        $user = (new User())->saveUser($registerDto);
        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return SuccessResponse::setData(ResponseCodes::OK, $user->getId());
        } catch (\Exception $exception) {
            return ErrorRequest::setErrorException($exception->getMessage());
        }

    }

    public function login(LoginDto $loginDto): ?int
    {
        $user = $this->authRepository->getUser($loginDto);
        if ($user && password_verify($loginDto->password, $user->getHashPassword())) {
            return $user->getId();
        }
        return null;
    }

    public function saveUserRefreshToken(int $userId, string $token)
    {
        $userEntity = $this->authRepository->getUserById($userId);
        $userEntity->setRefreshToken($token);
        $this->entityManager->persist($userEntity);
        $this->entityManager->flush();
    }
}
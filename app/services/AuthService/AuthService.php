<?php


namespace app\services\AuthService;


use app\components\logger\LoggerComponent;
use app\dto\Auth\LoginDto;
use app\dto\Auth\RegisterDto;
use app\entities\User;
use app\entities\UserAuthEntity;
use app\repositories\AuthRepository\AuthRepository;
use app\Requests\ErrorRequest;
use app\Requests\ResponseCodes;
use app\Requests\SuccessResponse;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;

class AuthService
{
    private AuthRepository $authRepository;
    private EntityManager $entityManager;
    private LoggerComponent $loggerComponent;

    public function __construct(AuthRepository $authRepository)
    {
        $this->entityManager = app()->config('entityManager');
        $this->authRepository = $authRepository;
        $this->loggerComponent = app()->config('container')->get('logger');
    }

    public function register(RegisterDto $registerDto): array
    {
        $this->loggerComponent->log(Logger::INFO, 'Auth Service->register run, data:' . json_encode($registerDto));
        if ($this->authRepository->getUser($registerDto)) {
            $this->loggerComponent->log(Logger::INFO, 'Auth Service->register user already exist');
            return ErrorRequest::setErrorWithCode(ResponseCodes::USER_ALREADY_EXISTS);
        }

        $user = (new User())->saveUser($registerDto);
        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->loggerComponent->log(Logger::INFO, 'Successfully created user');
            return SuccessResponse::setData(ResponseCodes::OK, $user->getId());
        } catch (\Exception $exception) {
            $this->loggerComponent->log(Logger::INFO, 'Auth Service->register, error:' . $exception->getMessage());
            return ErrorRequest::setErrorException($exception->getMessage());
        }

    }

    public function login(LoginDto $loginDto): ?int
    {
        $this->loggerComponent->log(Logger::INFO, 'Auth Service->login run, data:' . json_encode($loginDto));
        $user = $this->authRepository->getUser($loginDto);
        if ($user && password_verify($loginDto->password, $user->getHashPassword())) {
            $this->loggerComponent->log(Logger::INFO, 'Auth Service->login success:' . json_encode($loginDto));
            return $user->getId();
        }
        $this->loggerComponent->log(Logger::INFO, 'Auth Service->login error: wrong user credentials');
        return null;
    }

    public function saveUserRefreshToken(int $userId, string $token): void
    {
        $userEntity = $this->authRepository->getUserById($userId);
        UserAuthEntity::getInstance()->setUser($userEntity);
        $userEntity->setRefreshToken($token);
        $this->entityManager->persist($userEntity);
        $this->entityManager->flush();
    }
}
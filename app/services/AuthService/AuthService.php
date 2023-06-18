<?php


namespace app\services\AuthService;


use app\dto\Auth\RegisterDto;
use app\entities\User;
use app\repositories\AuthRepository\AuthRepository;
use app\Requests\ErrorRequest;
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
        if ($this->authRepository->isExistUser($registerDto)) {
            return ErrorRequest::setError('user already exist');
        }

        $user = (new User())->saveUser($registerDto);
        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return SuccessResponse::setData('succesfully created');
        } catch (\Exception $exception) {
            return ErrorRequest::setError($exception->getMessage());
        }

    }
}
<?php


namespace app\repositories\AuthRepository;


use app\dto\Auth\RegisterDto;
use app\dto\BaseDto;
use app\entities\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class AuthRepository
{

    private EntityManager $entityManager;
    private EntityRepository $userRepository;

    public function __construct()
    {
        $this->entityManager = app()->config('entityManager');
        $this->userRepository = $this->entityManager->getRepository(User::class);
    }


    public function getUser(BaseDto $dto): ?User
    {
       return $this->userRepository->findOneBy(['email' => $dto->email]);

    }

    public function getUserById(int $userId): ?User
    {
        return $this->userRepository->findOneBy(['id' => $userId]);
    }

    public function getUserByRefreshToken(string $refreshToken): ?User
    {
        return $this->userRepository->findOneBy(['refresh_token' => $refreshToken]);
    }
}
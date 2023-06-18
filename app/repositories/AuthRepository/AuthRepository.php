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


    public function isExistUser(RegisterDto $dto): object | null
    {
       return $this->userRepository->findOneBy(['email' => $dto->email]);

    }
}
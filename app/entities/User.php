<?php

namespace app\entities;

use app\dto\Auth\RegisterDto;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User
{

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $login;

    #[ORM\Column(type: 'string', length: 255, unique:true)]
    private string $email;

    #[ORM\Column(type: 'string', length: 255)]
    private string $password;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $refresh_token;

    // Геттеры и сеттеры для свойств
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $name): void
    {
        $this->login = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function setRefreshToken(string $refreshToken)
    {
        $this->refresh_token = $refreshToken;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refresh_token;
    }

    public function getHashPassword(): string
    {
        return $this->password;
    }

    public function isPasswordCorrect(string $hash): bool
    {
       return password_verify($this->password, $hash);
    }

    public function saveUser(RegisterDto $registerDto): self
    {
        $this->setEmail($registerDto->email);
        $this->setLogin($registerDto->login);
        $this->setPassword($registerDto->password);
        return $this;
    }
}
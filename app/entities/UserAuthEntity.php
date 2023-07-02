<?php


namespace app\entities;


class UserAuthEntity
{
    private static $instance = null;

    public User $user;

    private function __construct()
    {

    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

}
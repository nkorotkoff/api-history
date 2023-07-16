<?php


namespace tests\auth;


use app\entities\User;

class TestUser extends User
{
    public function __construct(int $id)
    {
        $this->setId($id);
    }
}
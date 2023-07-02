<?php


namespace app\dto\Auth;


use app\dto\BaseDto;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class LoginDto extends BaseDto
{
    public string $email;

    public string $password;

    public ?string $error = null;


    public function validate(): void
    {
        $validator = v::attribute('email', v::stringType()->notEmpty())
            ->attribute('password', v::stringType()->notEmpty());

        try {
            $validator->assert($this);
        } catch (NestedValidationException $exception) {
            $this->error = $exception->getFullMessage();
        }
    }
}
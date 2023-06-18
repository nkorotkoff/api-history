<?php
namespace app\dto\Auth;

use app\dto\BaseDto;
use Psr\Log\InvalidArgumentException;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

class RegisterDto extends BaseDto
{
    public string $login;

    public string $password;

    public string $email;

    public ?string $error = null;

    public function validate(): void
    {
        $validator = v::attribute('login', v::stringType()->notEmpty())
            ->attribute('password', v::stringType()->notEmpty())
            ->attribute('email', v::email()->notEmpty());

        try {
            $validator->assert($this);
        } catch (NestedValidationException $exception) {
            $this->error = $exception->getFullMessage();
        }
    }
}
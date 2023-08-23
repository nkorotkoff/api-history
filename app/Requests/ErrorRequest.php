<?php


namespace app\Requests;


use Leaf\Http\Request;
use Leaf\Http\Response;

class ErrorRequest
{
    const EXCEPTION = 500;

    public static function setErrorWithCode(int $errorCode): array
    {
       return ['message' => ResponseCodes::MESSAGES[$errorCode], 'code' => $errorCode];
    }

    public static function setErrorException(string $message): array
    {
        return ['message' => $message, 'code' => self::EXCEPTION];
    }
}
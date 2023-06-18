<?php


namespace app\Requests;


use Leaf\Http\Request;
use Leaf\Http\Response;

class ErrorRequest
{

    const BAD_PARAM = 403;

    public static function setError(string $message): array
    {
       return ['message' => $message, 'code' => self::BAD_PARAM];
    }
}
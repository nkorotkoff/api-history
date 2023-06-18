<?php


namespace app\Requests;


class SuccessResponse
{
    const OK = 200;

    public static function setData(string $message): array
    {
        return ['data' => $message, 'code' => self::OK];
    }
}
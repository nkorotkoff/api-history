<?php


namespace app\Requests;


class SuccessResponse
{


    public static function setData(int $successCode, mixed $data): array
    {
        return ['message' => ResponseCodes::MESSAGES[$successCode], 'code' => $successCode, 'result' => $data];
    }
}
<?php


namespace app\Requests;


class ResponseCodes
{
    const USER_ALREADY_EXISTS = 1;
    const OK = 2;
    const WRONG_USER_CREDENTIALS = 3;
    const USER_TOKENS_WRONG = 4;
    const ERROR_SAVING_DATA_IN_DATABASE = 5;

    const MESSAGES = [
        self::USER_ALREADY_EXISTS => 'User already exists',
        self::OK => 'OK',
        self::WRONG_USER_CREDENTIALS => 'Wrong user credentials',
        self::USER_TOKENS_WRONG => 'User tokens are wrong',
        self::ERROR_SAVING_DATA_IN_DATABASE => 'Error saving data in database',
    ];
}
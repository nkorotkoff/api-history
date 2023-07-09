<?php


namespace swagger\Controllers;


use OpenApi\Annotations as OA;

abstract class AuthController
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"User"},
     *     summary="Вход",
     *     description="Вход",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="email", type="string", example="user@example.com"),
     *             @OA\Property(property="password", type="string", example="mypassword"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный вход",
     *              @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="OK"),
     *             @OA\Property(property="code", type="string", example="2"),
     *             @OA\Property(property="result", type="string", example="12")
     *         )
     *     ),
     *
     *
     * @OA\Response(
     *         response=400,
     *         description="Введеный неверный данные",
     *              @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Wrong user credentials"),
     *             @OA\Property(property="code", type="string", example="3"),
     *         )
     *     )
     * )
     */
    public function login()
    {
    }


    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"User"},
     *     summary="Регистрация пользователя",
     *     description="Регистрация нового пользователя",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="email", type="string", example="user@example.com"),
     *             @OA\Property(property="password", type="string", example="mypassword"),
     *             @OA\Property(property="login", type="string", example="John Doe")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешная регистрация",
     *              @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="OK"),
     *             @OA\Property(property="code", type="string", example="2"),
     *             @OA\Property(property="result", type="string", example="12")
     *         )
     *     ),
     *
     *
     * @OA\Response(
     *         response=409,
     *         description="Пользователь уже существует",
     *              @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="User already exist"),
     *             @OA\Property(property="code", type="string", example="1"),
     *         )
     *     )
     * )
     */
    public function register()
    {
    }

}
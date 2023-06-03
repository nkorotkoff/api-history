<?php


namespace app\controllers;


use Leaf\Http\Request;
use Leaf\Http\Response;

class Controller
{
    public ?Response $response;

    public ?Request $request;

    public function __construct()
    {
        $this->response = response();
        $this->request = request();
    }


    public function view($string)
    {
        $dir = __DIR__ . $string;
        $this->response->page($dir);
    }

}
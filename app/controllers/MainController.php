<?php

namespace app\controllers;

class MainController extends Controller
{

    public function index()
    {
        $this->view( '/../../welcome.html');
    }
}
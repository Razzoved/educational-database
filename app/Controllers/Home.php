<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return $this->getIndex();
    }

    public function getIndex()
    {
        return view('welcome_message');
    }
}

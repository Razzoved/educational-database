<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AdminHome extends BaseController
{
    public function getIndex()
    {
        echo '<h1>This is an admin home area</h1>';
    }

    public function getTry($arg1, $arg2)
    {
        echo '<p>Trying ' . $arg1 . ' and ' . $arg2 . '</p>';
    }
}

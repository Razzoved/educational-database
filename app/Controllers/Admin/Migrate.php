<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use Throwable;

class Migrate extends Controller
{
    public function index()
    {
        $migrate = \Config\Services::migrations();

        try {
            echo 'Starting migration<br>';
            if ($migrate->latest()) {
                echo 'Migration finished!<br>';
            } else {
                echo 'Migration failed!<br>';
            }
        } catch (Throwable $e) {
            echo '<hr>' . $e->getMessage() . '<br>';
        }
    }

    public function back()
    {
        $migrate = \Config\Services::migrations();

        try {
            echo 'Starting rollback of last migration<br>';
            if ($migrate->regress(-1)) {
                echo 'Rollback successfully completed!<br>';
            } else {
                echo 'Rollback failed!<br>';
            }
        } catch (\Exception $e) {
            echo '<hr>' . $e->getMessage() . '<br>';
        }
    }
}
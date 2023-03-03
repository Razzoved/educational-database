<?php declare(strict_types = 1);

namespace App\Models;

use CodeIgniter\Model;

abstract class AbstractModel extends Model
{
    protected const PREFIX = 'edmat_';
}

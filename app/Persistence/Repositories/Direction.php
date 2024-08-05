<?php declare(strict_types=1);

namespace App\Persistence\Repositories;

enum Direction: string
{
    case ASC = 'asc';
    case DESC = 'desc';
}

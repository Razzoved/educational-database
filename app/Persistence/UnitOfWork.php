<?php declare(strict_types=1);

namespace App\Persistence;

final class UnitOfWork
{
    private $db;

    public function __construct(Dataq1$db)
    {
        $this->db = $db;
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\RawSql;

class Helper
{
    public static function getAddIdQuery(string $prefixedTabled): RawSql
    {
        return new RawSql(
            'ALTER TABLE' . ' ' .
                $prefixedTabled . ' ' .
                'ADD `id` BIGINT PRIMARY KEY AUTO_INCREMENT FIRST'
        );
    }
}

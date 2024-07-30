<?php

declare(strict_types=1);

namespace App\Persistence;

use PHPUnit\TextUI\Exception;

final class UnitOfWork
{
    private $db;
    private $transactions = [];

    public function __construct()
    {
        $this->db = \Config\Services::database();
    }

    public function beginTransaction()
    {
        $this->db->transStart();
    }

    public function commit()
    {
        $this->db->transComplete();
        if ($this->db->transStatus() === false)
        {
            // handle the failure
            throw new \Exception('Transaction failed');
        }
    }

    public function rollback()
    {
        $this->db->transRollback();
    }

    public function registerTransaction(callable $callback)
    {
        $this->transactions[] = $callback;
    }

    public function execute()
    {
        try {
            $this->beginTransaction();
            foreach ($this->transactions as $transaction) {
                $transaction();
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }
}

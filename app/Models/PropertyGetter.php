<?php declare(strict_types = 1);

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class PropertyGetter
{
    protected ConnectionInterface $db;

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    public function getByType(string $type) : array
    {
        return $this->db->table('properties')
            ->select('property_value')
            ->where('property_type', $type, true)
            ->orderBy('property_value')
            ->get()
            ->getResultArray();
    }

    public function getTypes() : array
    {
        return $this->db->table('properties')
            ->select('property_type')
            ->orderBy('property_type')
            ->distinct()
            ->get()
            ->getResultArray();
    }
}
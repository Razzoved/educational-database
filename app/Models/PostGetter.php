<?php declare(strict_types = 1);

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class PostGetter
{
    protected ConnectionInterface $db;

    public function __construct(ConnectionInterface $db) {
        $this->db = $db;
    }

    public function all() : array {
        return $this->db->table('posts')->get()->getResult('array');
    }

    public function filtered(string $userInput) : array {
        // todo add filtering from filters aside from search
        return $this->db->table('posts')
            ->orLike(
                ['post_title' => $userInput],
                escape: true, insensitiveSearch: true)
            ->get()
            ->getResult('array');
    }

    private function getLast($table) : array {
        return $table->orderBy(['post_id', 'DESC'])->get()->getRow();
    }
}
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
                ['post_title' => $userInput, 'post_content' => $userInput],
                escape: true, insensitiveSearch: true)
            ->get()
            ->getResult('array');
    }
}
<?php declare(strict_types = 1);

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Database\RawSql;

class PostGetter
{
    protected ConnectionInterface $db;

    public function __construct(ConnectionInterface $db) {
        $this->db = $db;
    }

    public function all() : array {
        return $this->db->table('posts')->get()->getResult('array');
    }

    public function filtered(string $userInput, array $filters) : array {
        return $this->db->table('posts')
            ->orLike(
                ['post_title' => $userInput, 'post_content' => $userInput],
                escape: true,
                insensitiveSearch: true)
            ->get()->getResult('array');
    }

    /**
     * UNUSED TILL DONE
     */
    private function wipFilter() {
        // TODO: zopakovat si sql
        return $this->db->query(
            '
            SELECT *
            FROM (
                SELECT posts_properties.post_id, properties.property_id properties
                FROM properties JOIN posts_properties
                ON properties.property_id=posts_properties.property_id
                WHERE properties.property_type="author" AND properties.property_value IN "authorArray"
            )
            '
        );
    }

    private function getAllWith($key, $values) {
        $condition = 'posts_properties.property_id=properties.property_id';
        return $this->db->table('properties')
            ->select('post_id')
            ->join('posts_properties', $condition)
            ->where('property_type', $key)
            ->whereIn('property_value', $values);
    }
}
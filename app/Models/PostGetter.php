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
                escape: true, insensitiveSearch: true)
            ->join($this->allFilters($filters), $subQuery['id'] = 'posts.post_id')
            ->get()
            ->getResult('array');
    }

    private function allFilters(array $filters) : string {
        $sql1 = "properties.property_id = posts_properties.property_id";
        $sql2 = "posts.post_id = posts_properties.post_id";
        foreach ($filters as $k => $v) {
            if (isset($subQuery)) $prevQuery = $subQuery;
            $subQuery = $this->db->table('properties')
                ->select('property_id')
                ->where('property_type', $k)
                ->whereIn('property_value', $v)
                ->join('posts_properties', new RawSql($sql1), 'INNER')
                ->join('posts', new RawSql($sql2), 'INNER');
            if (isset($prevQuery)) $subQuery->union($prevQuery);
        }
        return $subQuery->getCompiledSelect();
    }
}
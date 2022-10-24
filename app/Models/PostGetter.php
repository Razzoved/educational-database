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
        // $builder = $this->db->table('posts');
        // $builder->orLike(
        //     ['post_title' => $userInput, 'post_content' => $userInput]
        //     ,escape: true,
        //     insensitiveSearch: true);

        $total = 0;

        echo '<pre>';
        print_r($filters);
        echo '</pre>';

        // FIRST PART
        $builder = $this->db->table('properties t1');

        $builder->select('t2.post_id, property_type, property_value');
        $builder->selectCount('t2.property_id', 'count');

        $builder->join('posts_properties t2', 't1.property_id=t2.property_id');

        $builder->groupBy('t2.post_id');
        $builder->havingGroupStart();
        foreach ($filters as $k => $v) {
            $builder->orHavingGroupStart()
                ->having('property_type', $k)
                ->havingIn('property_value', array_keys($v))
                ->havingGroupEnd();
            $total += count($v);
        }
        $builder->havingGroupEnd();

        // var_dump($builder->getCompiledSelect());

        // SECOND PART
        $x = $this->db->table('posts');
        $x->join( $builder, 'b')->where('b.count', $total);
        $x->orLike([
            'post_title' => $userInput,
            'post_content' => $userInput
        ], escape: true, insensitiveSearch: true);
        // var_dump($x->getCompiledSelect());

        $res = $x->get()->getResultArray();

        echo '<pre>';
        print_r($res);
        echo '</pre>';

        return $res;
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
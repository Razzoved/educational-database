<?php declare(strict_types = 1);

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class FilterModel
{

    protected ConnectionInterface $db;

    public function __construct(ConnectionInterface $db) {
        $this->db = $db;
    }

    public function all() : array {
        return $this->getArray($this->db->table('posts'));
    }

    // TODO: implement filtering
    public function filtered() : array {
        return $this->getArray($this->db->table('posts')->where(['post_id <' => 20]));
    }

    private function getArray($table) : array {
        return $table->get()->getResult();
    }

    private function getFirst($table) : object {
        return $table->get()->getRow();
    }

    private function getLast($table) : object {
        return $table->orderBy(['post_id', 'DESC'])->get()->getRow();
    }
}
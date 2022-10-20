<?php declare(strict_types = 1);

namespace App\Models\Tables;

use CodeIgniter\Model;

class PostModel extends Model
{
    protected $table      = 'posts';
    protected $primaryKey = 'post_id';
    protected $useAutoIncrement = true;

    // protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    // TODO: might need to add views and rating here
    protected $allowedFields = ['post_title', 'post_thumbnail', 'post_type', 'post_content', 'post_is_public'];

    protected $useTimestamps = true;
    protected $createdField  = 'post_created_at';
    protected $updatedField  = 'post_updated_at';
    // protected $deletedField  = 'post_deleted_at';

    // protected $validationRules    = [];
    // protected $validationMessages = [];
    // protected $skipValidation     = false;

    public function all() : array
    {
        return $this->db->table($this->table)
            ->get()
            ->getResult('array');
    }

    // public function search() : array
    // {
    //     $search_input = $this->input->GET('search');
    //     return $this->filtered($search_input);
    // }

    private function filtered(string $userInput) : array
    {
        // $applied_filters = $this->input->GET('filters');
        return $this->db->table($this->table)
            ->orLike(
                ['post_title' => $userInput],
                escape: true, insensitiveSearch: true)
            ->get()
            ->getResult('array');
    }
}
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

    public function search()
    {
        $search_input = $this->input->GET('search');
        return $this->filtered($search_input);
    }

    private function filtered(string $userInput) : array {
        // todo add filtering from filters aside from search
        return $this->db->table('posts')
            ->orLike(
                ['post_title' => $userInput],
                escape: true, insensitiveSearch: true)
            ->get()
            ->getResult('array');
    }
}
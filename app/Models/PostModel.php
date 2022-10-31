<?php declare(strict_types = 1);

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Post;

class PostModel extends Model
{
    protected $table         = 'posts';
    protected $primaryKey    = 'post_id';
    protected $allowedFields = [
        'post_title',
        'post_thumbnail',
        'post_type',
        'post_content',
        'post_is_public'
    ];

    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;

    protected $createdField  = 'post_created_at';
    protected $updatedField  = 'post_updated_at';

    protected $returnType = Post::class;

    public function all() : array
    {
        return $this->findAll();
    }

    public function search(string $userInput) : array
    {
        return $this->db->table($this->table)
            ->orLike(
                ['post_title' => $userInput],
                escape: true, insensitiveSearch: true)
            ->get()
            ->getResultArray();
    }

    public function filter(string $userInput, array $filters): array
    {
        return [];
    }
}

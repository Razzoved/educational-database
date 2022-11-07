<?php declare(strict_types = 1);

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Post;

class PostModel extends Model
{
    protected $table         = 'posts';
    protected $primaryKey    = 'post_id';
    protected $allowedFields = [
        'post_is_public',
        'post_title',
        'post_thumbnail',
        'post_type',
        'post_content',
        'post_views',
    ];

    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;

    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $returnType = Post::class;

    public function findAll(int $limit = 0, int $offset = 0) : array
    {
        return $this->select('*')
                    ->where('post_is_public !=', false)
                    ->get()
                    ->getCustomResultObject(Post::class);
    }

    public function findWithProperties(int $id) : Post|null
    {
        $post = $this->find($id);
        if (!$post || $post->post_is_public == false) return null;
        $post->properties = (new PostsPropertiesModel())->findProperties($id);
        return $post;
    }

    public function filter(string $search, array $filters, int $limit, int $offset): array
    {
        $connector = new PostsPropertiesModel();
        $f = $connector->getCompiledFilter($filters);

        $posts = $this->select("$this->table.*")
                      ->join("($f) f", "$this->table.post_id = f.post_id")
                      ->like('post_title', $search, insensitiveSearch: true)
                      ->where('post_is_public !=', false)
                      ->limit($limit, $offset * $limit)
                      ->get()
                      ->getCustomResultObject(Post::class);

        return $posts;
    }

    public function getUsedProperties() : array
    {
        $visibleIds = $this->select('post_id')
                           ->where('post_is_public !=', false)
                           ->get()
                           ->getResultArray();
        helper('array');
        $visibleIds = dot_array_search('*.post_id', $visibleIds);
        return (new PostsPropertiesModel())->getUsedProperties($visibleIds);
    }
}

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
        'post_is_public',
        'post_views',
        'post_rating'
    ];

    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;

    protected $createdField  = 'post_created_at';
    protected $updatedField  = 'post_updated_at';

    protected $returnType = Post::class;

    public function findWithProperties(int $id) : Post|null
    {
        $post = $this->find($id);
        if ($post) {
            $post->properties = (new PostsPropertiesModel())->findProperties($id);
        }
        return $post;
    }

    public function filter(string $search, array $filters): array
    {
        $connector = new PostsPropertiesModel();
        $f = $connector->getCompiledFilter($filters);

        $posts = $this->select("$this->table.*")
                      ->join("($f) f", "$this->table.post_id = f.post_id")
                      ->like('post_title', $search, insensitiveSearch: true)
                      ->get()
                      ->getResult();

        return $posts;
    }

    public function getUsedProperties() : array
    {
        return (new PostsPropertiesModel())->getUsedProperties();
    }
}

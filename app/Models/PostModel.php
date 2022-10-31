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

    public function findWithTags(int $id) : Post|null
    {
        $post = $this->find($id);
        if ($post) {
            $post->properties = (new PostsPropertiesModel())->findTags($id);
        }
        return $post;
    }

    public function filter(string $userInput, array $filters): array
    {
        $f = (new PostsPropertiesModel())->getCompiledFilter($filters);

        $posts = $this->asObject('Post')
                      ->select("$this->table.*")
                      ->join("($f) f", "$this->table.post_id = f.post_id")
                      ->like(
                          ['post_title' => $userInput],
                          escape: true, insensitiveSearch: true)
                      ->get()
                      ->getResult();

        foreach ($posts as $post) {
            $post->properties = (new PostsPropertiesModel())->findTags((int) $post->post_id);
        }

        return $posts;
    }
}

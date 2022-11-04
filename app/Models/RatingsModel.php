<?php declare(strict_types = 1);

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Rating;

class MaterialModel extends Model
{
    protected $table         = 'ratings';
    protected $allowedFields = [
        'post_id',
        'rating_uid',
        'rating_value'
    ];
    protected $returnType = Rating::class;

    public function getRatingAvg(int $postId) : int
    {
        return $this->selectAvg('rating_value')
                    ->where('post_id', $postId)
                    ->get()
                    ->getResult();
    }

    public function getRatingCount(int $postId) : int
    {
        return $this->selectCount('*')
                    ->where('post_id', $postId)
                    ->get()
                    ->getResult();
    }

    public function findRating(int $postId, string $userId) : Rating|null
    {
        return $this->find(['post_id' => $postId, 'rating_uid' => $userId]);
    }
}

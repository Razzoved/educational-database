<?php declare(strict_types = 1);

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Rating;

class RatingsModel extends Model
{
    protected $table         = 'ratings';
    protected $allowedFields = [
        'material_id',
        'rating_uid',
        'rating_value'
    ];
    protected $returnType = Rating::class;

    public function getRatingAvg(int $postId) : float
    {
        return array_sum(array_column(
            $this->builder()->selectAvg('rating_value', 'rating')
                            ->where('material_id', $postId)
                            ->groupBy('material_id')
                            ->get()
                            ->getResult(),
            'rating'
        ));
    }

    public function getRatingCount(int $postId) : int
    {
        return array_sum(array_column(
            $this->builder()
                 ->selectCount('rating_value', 'count')
                 ->where('material_id', $postId)
                 ->groupBy('material_id')
                 ->get()
                 ->getResult(),
            'count'
        ));
    }

    public function getRating(int $postId, string $userId) : Rating|null
    {
        return $this->builder()
                     ->where('material_id', $postId)
                     ->where('rating_uid', $userId)
                     ->get(1)
                     ->getCustomResultObject(Rating::class);
    }
}

<?php declare(strict_types = 1);

namespace App\Models;

use App\Entities\Rating;

class RatingsModel extends AbstractModel
{
    protected $table         = parent::PREFIX . 'ratings';
    protected $allowedFields = [
        'material_id',
        'rating_uid',
        'rating_value'
    ];
    protected $returnType = Rating::class;

    public function setRating(int $materialId, string $userId, ?int $value) : bool
    {
        $old = $this->getRating($materialId, $userId);
        if ($old) {
            $this->db->table($this->table)
                     ->update(['rating_value' => $value], ['material_id' => $materialId, 'rating_uid' => $old->rating_uid], 1);
        } else {
            $this->db->table($this->table)
                     ->insert(['material_id' => $materialId, 'rating_uid' => $userId, 'rating_value' => $value]);
        }

        return $old === null || $old->rating_value !== $value;
    }

    public function getRatingAvg(int $materialId) : float
    {
        return array_sum(array_column(
            $this->builder()->selectAvg('rating_value', 'rating')
                            ->where('material_id', $materialId)
                            ->groupBy('material_id')
                            ->get()
                            ->getResult(),
            'rating'
        ));
    }

    public function getRatingCount(int $materialId) : int
    {
        return array_sum(array_column(
            $this->builder()
                 ->selectCount('rating_value', 'count')
                 ->where('material_id', $materialId)
                 ->groupBy('material_id')
                 ->get()
                 ->getResult(),
            'count'
        ));
    }

    public function getRating(int $materialId, string $userId) : Rating|null
    {
        return $this->builder()
                     ->where('material_id', $materialId)
                     ->where('rating_uid', $userId)
                     ->get(1)
                     ->getCustomResultObject(Rating::class);
    }
}

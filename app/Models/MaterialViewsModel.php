<?php declare(strict_types = 1);

namespace App\Models;

use CodeIgniter\Model;

class MaterialViews extends Model
{
    protected $table = 'material_views';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'material_id',
        'material_views'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'date';
    protected $createdField = 'created_at';

    public function getTopMaterials(int $n, int $days = 30) : array
    {
        return $this->builder()
                    ->select('material_id')
                    ->selectSum('material_views')
                    ->groupBy('material_id')
                    ->orderBy('material_views', 'desc')
                    ->where('created_at >=', time() - $days * 86400)
                    ->get($n)
                    ->getResultArray();
    }

    public function increment(int $id) : int
    {
        $last = end($this->builder()
                         ->select('*')
                         ->where('material_id', $id)
                         ->get()
                         ->getResultArray());

        $views = 0;

        if ($last === false || $last['created_at'] !== date('Y-m-d', time())) {
            $this->insert(['material_id' => $id, 'material_views' => ++$views]);
        } else {
            $views += $last['material_views'];
            $this->update($last['id'], ['material_views' => ++$views]);
        }

        return $views;
    }
}

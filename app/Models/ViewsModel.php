<?php declare(strict_types = 1);

namespace App\Models;

use App\Entities\Material;
use CodeIgniter\Model;

class ViewsModel extends Model
{
    protected $table = 'views';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'material_id',
        'material_views'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'date';
    protected $createdField = 'created_at';
    protected $updatedField = '';

    public function getTopMaterials(int $n, int $days = 30) : array
    {
        $ids = $this->builder()
                    ->select('material_id')
                    ->selectSum('material_views')
                    ->groupBy('material_id')
                    ->orderBy('material_views', 'desc')
                    ->where('created_at >=', date('Y-m-d', time() - $days * 86400))
                    ->get($n)
                    ->getResultArray();

        $materials = array();
        foreach ($ids as $index => $arr) {
            $material = model(MaterialModel::class)->getById((int) $arr['material_id']);
            $material->views = $arr['material_views'];
            $materials[] = $material;
        }

        return $materials;
    }

    /**
     * Handles the update of views of given material id ONLY inside the
     * views table.
     */
    public function increment(Material $material) : void
    {
        $all = $this->builder()
            ->select('*')
            ->where('material_id', $material->id)
            ->get()
            ->getResultArray();
        $last = end($all);
        $views = 0;

        if ($last === false || $last['created_at'] !== date('Y-m-d', time())) {
            $this->insert(['material_id' => $material->id, 'material_views' => ++$views]);
        } else {
            $views += $last['material_views'];
            $this->update($last['id'], ['material_views' => ++$views]);
        }

        $material->views++;
        model(MaterialModel::class)->update($material->id, $material);
    }
}

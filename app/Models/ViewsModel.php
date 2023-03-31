<?php declare(strict_types = 1);

namespace App\Models;

use App\Entities\Material;
use CodeIgniter\Model;
use Exception;

/**
 * This model handles the operations over the views table. The table
 * stores the viewcount information for each material on daily basis
 * (ie. increments the views for current day, or creates a new record).
 *
 * The model also provides a retrieval method for the latest most
 * viewed materials.
 *
 * @author Jan Martinek
 */
class ViewsModel extends Model
{
    protected $table = 'views';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'material_id',
        'material_views'
    ];

    protected $useAutoIncrement = true;
    protected $useTimestamps = true;
    protected $dateFormat = 'date';
    protected $createdField = 'created_at';
    protected $updatedField = '';

    protected $returnType = Material::class;

    /**
     * Gets an array of total views from past 'x' days.
     * The array is filled with zeroes on empty days.
     */
    public function getDailyTotals(int $days = 30) : array
    {
        $views = $this->builder()
                    ->select('created_at')
                    ->selectSum('material_views')
                    ->groupBy('created_at')
                    ->orderBy('created_at', 'asc')
                    ->where('created_at >', date('Y-m-d', time() - $days * 86400))
                    ->get($days)
                    ->getResultArray();

        $result = [];
        $count = $days - 1;
        foreach ($views as $index => $arr) {
            $lastDate = date('Y-m-d', time() - $count-- * 86400);
            while ($arr['created_at'] !== $lastDate) {
                $result[] = 0;
                $lastDate = date('Y-m-d', time() - $count-- * 86400);
            }
            $result[] = $arr['material_views'];
        }
        return $result;
    }

    /**
     * Grabs the 'n' most viewed materials and returns them as a numbered
     * array. This array is already ordered by views. The view counts are
     * also loaded into the material in place of the total views.
     *
     * @param int $n maximum number of materials to return
     * @param int $days number of days to look back
     */
    public function getTopMaterials(int $n = 0, int $days = 30) : array
    {
        $views = $this->select('material_id')
            ->selectSum('material_views')
            ->groupBy('material_id')
            ->orderBy('material_views', 'desc')
            ->where('created_at >', date('Y-m-d', time() - $days * 86400))
            ->findAll($n);

        $materials = array();
        foreach ($views as $v) {
            $material = model(MaterialModel::class)->get($v->id);
            if ($material) {
                $material->views = $v->views;
                $materials[] = $material;
            }
        }

        return $materials;
    }

    /**
     * Handles the update of views of given material. Updates both this table
     * and the material table.
     *
     * @param Material $material entity we want to increment views of
     */
    public function increment(Material &$material) : void
    {
        $last = $this->where('material_id', $material->id)
                     ->orderBy('created_at', 'DESC')
                     ->first();
        try {
            if (!$last || $last->created_at !== date('Y-m-d', time())) {
                $this->insert(['material_id' => $material->id, 'material_views' => 1]);
            } else {
                $this->update($last->id, ['material_views' => $last->views + 1]);
            }
            $material->views++;
            model(MaterialModel::class)->update($material->id, $material);
        } catch (Exception $e) {
            // intentionally ignored
        }
    }

    /**
     * This method imports all the viewcounts from the materials table
     * to the views table, and sets their date to created_at from the
     * material.
     *
     * @warning throws away any previous data (including dates).
     * @depracated this is here to warn the developer
     */
    public function reimportMaterials()
    {
        $this->emptyTable($this->table);
        $this->db->query('ALTER TABLE ' . $this->table . ' AUTO_INCREMENT = 1');

        $lastKey = array_key_last($this->allowedFields);
        $materials = model(MaterialModel::class)->getData()
                                                ->get()
                                                ->getCustomResultObject(\App\Entities\Material::class);

        $this->allowedFields[] = 'created_at';
        foreach ($materials as $material) {
            $this->insert([
                'material_id' => $material->id,
                'material_views' => $material->views,
                'created_at' => $material->created_at->toDateString()
            ]);
        }
        $this->allowedFields[$lastKey + 1] = null;
    }
}

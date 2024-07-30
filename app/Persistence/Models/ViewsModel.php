<?php

declare(strict_types=1);

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
        'views',
        'material_id',
    ];

    protected $useAutoIncrement = true;
    protected $useTimestamps = true;
    protected $dateFormat = 'date';
    protected $createdField = 'created_at';
    protected $updatedField = '';
    protected $deletedField = '';

    protected $returnType = Material::class;

    /**
     * Gets an array of total views from past 'x' days.
     * The array is filled with zeroes on empty days.
     */
    public function getDailyTotals(int $days = 30): array
    {
        $views = $this->select('created_at')
            ->selectSum('views')
            ->groupBy('created_at')
            ->orderBy('created_at', 'desc')
            ->where('created_at >', $this->date($days))
            ->findAll($days);

        $result = [];
        foreach ($views as $view) {
            $result[$view->created_at->toDateString()] = $view->views;
        }
        for ($i = 0; $i < $days; $i++) {
            $date = $this->date($i);
            $result[$date] = $result[$date] ?? 0;
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
    public function getTopMaterials(int $n = 1, string $search = "", int $days = 30): array
    {
        // TODO: reimplement
        // $mTable = model(MaterialModel::class)->table;
        // $views = model(MaterialModel::class)
        //     ->select('*')
        //     ->selectSum('v.views', 'views')
        //     ->join("{$this->table} as v", "{$mTable}.id=v.material_id")
        //     ->groupBy('v.material_id')
        //     ->orderBy('views', 'desc')
        //     ->where('v.created_at >', $this->date($days))
        //     ->getArray(['callbacks' => false, 'sortBy' => 'published_at', 'sortDir' => 'desc', 'search' => $search], $n);

        // foreach ($views as $v) {
        //     $v->resources = model(ResourceModel::class)->getThumbnail($v->id);
        // }

        // return $views;
        return model(MaterialModel::class)->findAll();
    }

    /**
     * Handles the update of views of given material. Updates both this table
     * and the material table.
     *
     * @param Material $material entity we want to increment views of
     */
    public function increment(Material &$material): void
    {
        $last = $this->where('material_id', $material->id)
            ->orderBy('created_at', 'DESC')
            ->first();
        try {
            if (!$last || $last->created_at !== $this->date()) {
                $this->insert(['material_id' => $material->id, 'views' => 1]);
            } else {
                $this->update($last->id, ['views' => $last->views + 1]);
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
     * @info throws away any previous data (including dates).
     * @deprecated this is here to warn the developer
     */
    public function reimportMaterials()
    {
        $this->emptyTable($this->table);
        $this->db->query('ALTER TABLE ' . $this->table . ' AUTO_INCREMENT = 1');

        $materials = model(MaterialModel::class)->findAll();

        $this->allowedFields[] = 'created_at';
        foreach ($materials as $material) {
            $this->insert([
                'material_id' => $material->id,
                'views' => $material->views,
                'created_at' => $material->published_at->toDateString()
            ]);
        }
        array_pop($this->allowedFields);
    }

    private function date(int $offset = 0)
    {
        return date('Y-m-d', time() - $offset * 86400);
    }
}

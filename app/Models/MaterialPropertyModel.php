<?php

declare(strict_types=1);

namespace App\Models;

use App\Entities\Cast\StatusCast;
use App\Entities\Material;
use App\Libraries\Property as PropertyLib;
use CodeIgniter\Model;

/**
 * Conneting model between materials and their properties.
 * Its methods retrieve properties from property model.
 *
 * @author Jan Martinek
 */
class MaterialPropertyModel extends Model 
{
    protected $table = 'material_property';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'material_id',
        'property_id'
    ];
    protected $useAutoIncrement = true;

    protected $allowCallbacks = true;
    protected $afterInsert = [
        'revalidatePropertyCache',
    ];
    protected $afterDelete = [
        'revalidatePropertyCache',
    ];

    /** ----------------------------------------------------------------------
     *                           PUBLIC METHODS
     *  ------------------------------------------------------------------- */

    public function find($materialId = null): array
    {
        $this->distinct()
            ->select('property_id')
            ->where('material_id', $materialId);

        return PropertyLib::getFiltered(array_column($this->findAll(), 'property_id'));
    }

    public function getUsed(): array
    {
        $this->distinct()->select('property_id');

        if (!session('isLoggedIn')) {
            $this->join('materials as m', 'm.id=material_id')
                ->where('m.status', StatusCast::PUBLIC);
        }

        return PropertyLib::getFiltered(array_column($this->findAll(), 'property_id'));
    }

    /**
     * Compiles an SQL query, which returns all id's of materials, whose
     * proterties meet the filtering criteria. This query is not executed
     * and is returned as string.
     *
     * @param array $filters selected filters grouped by tags
     */
    public function getCompiledFilter(array $filters)
    {
        $materials = array_map(
            function ($material) {
                return $material->id;
            },
            model(MaterialModel::class)->allowCallbacks(false)->findAll()
        );

        $this->filterOr($materials, $filters['or'] ?? []);
        $this->filterAnd($materials, $filters['and'] ?? []);

        return empty($materials) ? [0] : $materials;
    }

    /**
     * Automatically decides whether to delete or insert a new property
     * to the material.
     *
     * @param material $material material to insert/delete with
     */
    public function saveMaterial(Material $material): bool
    {
        $this->db->transStart();

        $old = $this->select('id, property_id')
            ->where('material_id', $material->id)
            ->findAll();

        foreach ($material->properties ?? [] as $root) {
            PropertyLib::treeForEach($root, function ($p) use ($material, $old) {
                if (!empty($p->children)) {
                    return;
                }
                foreach ($old as $k => $oldObject) {
                    if ($p->id === $oldObject['property_id']) {
                        unset($old[$k]);
                        return;
                    }
                }
                $this->insert([
                    'material_id' => $material->id,
                    'property_id' => $p->id,
                ]);
            });
        }

        foreach ($old as $oldObject) {
            $this->delete($oldObject['id']);
        }

        $this->db->transComplete();

        return $this->db->transStatus();
    }

    /** ----------------------------------------------------------------------
     *                              CALLBACKS
     *  ------------------------------------------------------------------- */

    protected function revalidatePropertyCache(array $data)
    {
        if (isset($data['data']['property_id'])) {
            model(PropertyModel::class)->revalidateCache([
                'id' => $data['data']['property_id'],
            ]);
        }
        return $data;
    }


    /** ----------------------------------------------------------------------
     *                              HELPERS
     *  ------------------------------------------------------------------- */

    protected function filterOr(array &$filtered, array $groups): void
    {
        foreach ($groups as $ids) {
            $m = array_column(
                $this->select('material_id')
                    ->join('properties as p', 'p.id=property_id')
                    ->whereIn('p.id', $ids)
                    ->orWhereIn('p.tag', $ids)
                    ->findAll(),
                'material_id'
            );
            $filtered = array_intersect($filtered, $m);
        }
    }

    protected function filterAnd(array &$filtered, array $ids): void
    {
        foreach ($ids as $id) {
            $m = array_column(
                $this->select('material_id')
                    ->join('properties as p', 'p.id=property_id')
                    ->where('p.id', $id)
                    ->orWhere('p.tag', $id)
                    ->findAll(),
                'material_id'
            );
            $filtered = array_intersect($filtered, $m);
        }
    }
}

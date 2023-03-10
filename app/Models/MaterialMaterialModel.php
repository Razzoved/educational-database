<?php declare(strict_types = 1);

namespace App\Models;

use App\Entities\Cast\StatusCast;
use App\Entities\Material;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Model;

class MaterialMaterialModel extends Model
{
    protected $table = 'material_material';
    protected $primaryKey = 'material_id_left';
    protected $allowedFields = ['material_id_left', 'material_id_right'];

    /**
     * Looks for ALL pairs of materials where at least one member has
     * the given id. Returns an array of such materials.
     *
     * @param int $id           id of material whose tags we want to get
     * @param bool $onlyTitle   if true returns titles as values, else objects
     *
     * @return array of objects or strings
     */
    public function getRelated(int $id, bool $onlyTitle = false) : array
    {
        $ids = $this->builder()
                    ->select($this->allowedFields[0] . ' as l, ' . $this->allowedFields[1] . ' as r')
                    ->orWhere($this->allowedFields[0], $id)
                    ->orWhere($this->allowedFields[1], $id)
                    ->get()
                    ->getResultArray();

        $result = array();
        foreach ($ids as $key => $value) {
            if ($value['l'] === $value['r']) {
                continue; // same material
            }

            $identifier = $value['l'] == $id ? $value['r'] : $value['l'];
            $found = model(MaterialModel::class)->find($identifier);

            if ($found === null || (!session('isLoggedIn') && $found->status !== StatusCast::PUBLIC)) {
                continue;
            }

            if ($onlyTitle) {
                $result[$found->id] = $found->title;
            } else {
                $found->resources = model(ResourceModel::class)->getThumbnail($found->id);
                $result[$found->id] = $found;
            }
        }

        return $result;
    }

    /**
     * Automatically decides whether to delete or insert a new relationship
     * between two materials.
     *
     * @param Material $material material to insert/delete with
     * @param array $newRelations id => title pairs for update
     * @param BaseConnection $db database connection
     */
    public function handleUpdate(Material $material, array $newRelations, BaseConnection $db = null) : void
    {
        if (!isset($db)) $db = $this->db;

        $relations = $this->getRelated($material->id);

        $toDelete = array_filter($relations, function($r) use ($newRelations) {
            return $r && !in_array($r, $newRelations);
        });

        $toCreate = array_filter($newRelations, function($r) use ($relations) {
            return $r && !in_array($r, $relations);
        });

        foreach ($toDelete as $k => $v) {
            $db->table($this->table)
               ->orGroupStart()
                    ->where($this->allowedFields[0], $material->id)
                    ->where($this->allowedFields[1], $k)
               ->groupEnd()
               ->orGroupStart()
                    ->where($this->allowedFields[0], $k)
                    ->where($this->allowedFields[1], $material->id)
               ->groupEnd()
               ->delete();
        }

        foreach ($toCreate as $k => $v) {
            echo $k;
            $db->table($this->table)->insert([
                $this->allowedFields[0] => $material->id,
                $this->allowedFields[1] => $k,
            ]);
        }
    }
}

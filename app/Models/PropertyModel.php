<?php declare(strict_types = 1);

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Property;

class PropertyModel extends Model
{
    protected $table         = 'properties';
    protected $primaryKey    = 'property_id';
    protected $allowedFields = [
        'property_tag',
        'property_value'
    ];

    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = false;

    protected $returnType = Property::class;

    public function getByType(string $type) : array
    {
        return $this->db->query($this->getCompiledByType($type))
                        ->getResultArray();
    }

    public function getCompiledByType(string $type) : string
    {
        return $this->builder()
                    ->where('property_tag', $type)
                    ->orderBy('property_tag')
                    ->orderBy('property_value')
                    ->distinct()
                    ->getCompiledSelect();
    }

    public function getAll() : array
    {
        return $this->db->query($this->getCompiledAll())
                        ->getResultArray();
    }

    public function getCompiledAll() : string
    {
        return $this->builder()
                    ->orderBy('property_tag')
                    ->orderBy('property_value')
                    ->distinct()
                    ->getCompiledSelect();
    }

}

<?php declare(strict_types = 1);

namespace App\Models;

use App\Entities\Config;
use CodeIgniter\Model;
use Exception;

class ConfigModel extends Model
{
    protected $table         = 'config';
    protected $primaryKey    = 'config_id';
    protected $allowedFields = [
        'config_id',
        'config_value',
    ];

    protected $allowCallbacks = true;
    protected $beforeFind = [
        'checkCache'
    ];
    protected $afterUpdate = [
        'updateCache'
    ];
    protected $returnType = Config::class;

    public function update($id = null, $data = null) : bool
    {
        $retVal = parent::update($id, $data);
        if ($retVal) {
            $item = $this->find($id);

        }
        return $retVal;
    }

    /** ----------------------------------------------------------------------
     *                              CALLBACKS
     *  ------------------------------------------------------------------- */

    protected function checkCache($data)
    {
        if (!isset($data['data'])) {
            return $data;
        }

        echo $data;

        // if ($data['method'] === 'find') {
        //     $this->checkCache($data['data']->id);
        //     $data['data'] = $this->allowCallbacks(false)->find($data['data']->id);
        // } else foreach ($data['data'] as $k => $material) {
        //     if ($material) {
        //         $model = $model->allowCallbacks(false);
        //         $data['data'][$k] = $model->find($material->id);
        //     }
        // }

        return $data;
    }

    protected function _checkCache($id)
    {

    }
}

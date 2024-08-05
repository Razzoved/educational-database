<?php declare(strict_types=1);

namespace App\Persistence\Models;

use App\Libraries\Cache;
use App\Persistence\Entities\Config;
use CodeIgniter\Model;

class ConfigModel extends Model
{
    protected $table = 'config';
    protected $primaryKey = Config::ID;

    protected $allowedFields = [
        Config::ID,
        Config::VALUE,
    ];

    protected $useAutoIncrement = false;
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;
    protected $returnType = Config::class;

    /*
     * -------------------------------------------------------------------
     *                              CALLBACKS
     * --------------------------------------------------------------------
     */

    protected $allowCallbacks = true;
    protected $beforeFind = ['checkCache'];
    protected $afterFind = ['saveCache'];
    protected $afterUpdate = ['revalidateCache'];
    protected $afterDelete = ['revalidateCache'];

    protected function checkCache(array $data)
    {
        if (isset($data['id']) && $item = Cache::get($data['id'], 'config')) {
            $data['data'] = $item;
            $data['returnData'] = true;
        }
        return $data;
    }

    protected function saveCache(array $data)
    {
        if (!isset($data['data'])) {
            return $data;
        }
        if ($data['method'] !== 'findAll') {
            $item = Cache::get($data['data']->id, 'config');
            if (is_null($item)) {
                Cache::save($data['data'], $data['data']->id, 'config');
            }
        }
        return $data;
    }

    protected function revalidateCache(array $data)
    {
        if (!isset($data['id'])) {
            return;
        }
        foreach ($data['id'] as $id) {
            $item = Cache::get($id, 'config');
            if (!is_null($item)) {
                Cache::delete($id, 'config');
            }
        }
    }
}

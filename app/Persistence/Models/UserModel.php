<?php declare(strict_types=1);

namespace App\Persistence\Models;

use App\Persistence\Entities\User;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = User::ID;

    protected $allowedFields = [
        User::ALIAS,
        User::EMAIL,
        User::PASSWORD,
    ];

    protected $useAutoIncrement = true;
    protected $useTimestamps = true;
    protected $useSoftDeletes = User::DELETED !== '';
    protected $createdField = User::CREATED;
    protected $updatedField = User::UPDATED;
    protected $deletedField = User::DELETED;
    protected $returnType = User::class;

    /*
     * -------------------------------------------------------------------
     *                              CALLBACKS
     * --------------------------------------------------------------------
     */

    protected $allowCallbacks = true;
    protected $afterFind = ['hidePassword'];

    protected function hidePassword(array $data): array
    {
        if (!isset($data['data'])) {
            return $data;
        }

        if ($data['method'] === 'find') {
            $data['data']->password = null;
        } else {
            foreach ($data['data'] as $v) {
                if ($v) {
                    $v->password = null;
                }
            }
        }
        return $data;
    }
}

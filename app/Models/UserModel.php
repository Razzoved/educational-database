<?php declare(strict_types = 1);

namespace App\Models;

use App\Entities\User;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'user_id';
    protected $allowedFields = [
        'user_name',
        'user_email',
        'user_password',
    ];

    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;

    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];
    protected $afterFind    = ['hidePassword'];

    protected $returnType = User::class;

    /** ----------------------------------------------------------------------
     *                           PUBLIC METHODS
     *  ------------------------------------------------------------------- */

    public function get(int $id, array $data = []) : ?User
    {
        return $this->setupQuery($data)->find($id);
    }

    public function getArray(array $data = [], int $limit = 0) : array
    {
        return $this->setupQuery($data)->findAll($limit);
    }

    public function getPage(int $page = 1, array $data = [], int $perPage = 20) : array
    {
        return $this->setupQuery($data)->paginate($perPage, 'default', $page);
    }

    public function getEmail(string $email) : ?User
    {
        if ($email === "") {
            return null;
        }
        return $this->where('user_email', $email)
                    ->first();
    }

    public function deleteEmail(string $email) : void
    {
        $this->builder()
             ->delete(['user_email' => $email]);
    }

    /** ----------------------------------------------------------------------
     *                        UNIFIED QUERY SETUP
     *  ------------------------------------------------------------------- */

    protected function setupQuery(array $data = []) : UserModel
    {
        return $this
            ->setupSort($data['sort'] ?? "", $data['sortDir'] ?? "")
            ->setupFilters($data['filters'] ?? [])
            ->setupSearch($data['search'] ?? "");
    }

    protected function setupSort(string $sort, string $sortDir)
    {
        if (
            $sort !== $this->createdField &&
            $sort !== $this->updatedField &&
            $sort !== $this->primaryKey
        ) {
            $sort = 'user_' . $sort;
            $sort = in_array($sort, $this->allowedFields) || $sort === $this->primaryKey ? $sort : "";
        }

        if ($sort !== "") {
            $sort = $this->primaryKey;
        }

        $this->orderBy($sort, strtolower($sortDir) === 'desc' ? 'DESC' : 'ASC');

        if ($sort !== 'user_name') {
            $this->orderBy('user_name');
        }

        if ($sort !== 'user_email') {
            $this->orderBy('user_email');
        }

        return $this;
    }

    protected function setupFilters(array $filters)
    {
        foreach ($filters as $k => $v) {
            if (in_array($k, $this->allowedFields)) {
                if (is_array($v)) {
                    $this->whereIn($k, $v);
                } else {
                    $this->where($k, $v);
                }
            }
        }
        return $this;
    }

    protected function setupSearch(string $search)
    {
        if ($search === "") {
            return $this;
        }
        return $this->orLike('user_name', $search, 'both', true, true)
                    ->orLike('user_email', $search, 'both', true, true);
    }

    /** ----------------------------------------------------------------------
     *                              CALLBACKS
     *  ------------------------------------------------------------------- */

    protected function hashPassword(array $data) : array
    {
        if (!isset($data['data']['user_password'])) {
            return $data;
        }
        // prevent overwrite in case of empty password
        if ($data['data']['user_password'] === '') {
            unset($data['data']['user_password']);
        } else {
            $data['data']['user_password'] = password_hash(
                $data['data']['user_password'],
                PASSWORD_DEFAULT
            );
        }
        return $data;
    }

    protected function hidePassword(array $data) : array
    {
        if (!isset($data['data'])) {
            return $data;
        }
        if ($data['method'] === 'find') {
            $data['data']->password = null;
        } else foreach ($data['data'] as $k => $v) {
            if ($v) $v->password = null;
        }
        return $data;
    }
}

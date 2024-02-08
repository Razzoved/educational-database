<?php

declare(strict_types=1);

namespace App\Models;

use App\Entities\User;

class UserModel extends QueryModel
{
    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'name',
        'email',
        'password',
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

    public function findEmail(string $email): ?User
    {
        if ($email === "") {
            return null;
        }
        return $this->where('email', $email)->first();
    }

    /** ----------------------------------------------------------------------
     *                        UNIFIED QUERY SETUP
     *  ------------------------------------------------------------------- */

    protected function beforeQuery(array $data = []): self
    {
        return $this
            ->setupSort($data)
            ->setupFilters($data['filters'] ?? [])
            ->setupSearch($data['search'] ?? "");
    }

    protected function setupSort(array $data)
    {
        $this->sortBy($data);
        $this->sortDir($data);
        
        $this->orderBy($data['sortBy'], $data['sortDir']);

        if ($data['sortBy'] !== 'name') {
            $this->orderBy('name');
        }
        if ($data['sortBy'] !== 'email') {
            $this->orderBy('email');
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
        return $this
            ->orLike('name', $search, 'both', true, true)
            ->orLike('email', $search, 'both', true, true);
    }

    /** ----------------------------------------------------------------------
     *                              CALLBACKS
     *  ------------------------------------------------------------------- */

    protected function hashPassword(array $data): array
    {
        if (!isset($data['data']['password'])) {
            return $data;
        }
        // prevent overwrite in case of empty password
        if ($data['data']['password'] === '') {
            unset($data['data']['password']);
        } else {
            $data['data']['password'] = password_hash(
                $data['data']['password'],
                PASSWORD_DEFAULT
            );
        }
        return $data;
    }

    protected function hidePassword(array $data): array
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

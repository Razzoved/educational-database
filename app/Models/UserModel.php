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

    protected $returnType = User::class;

    public function getData(?string $sort = null, ?string $sortDir = null) : UserModel
    {
        $sortDir = $sortDir === 'DESC' ? $sortDir : 'ASC';
        $sort = $sort === $this->createdField || $sort === $this->updatedField
            ? $sort
            : (in_array('user_' . $sort, $this->allowedFields) || ('user_' . $sort === $this->primaryKey) ? ('user_' .  $sort) : null);

        $builder = $this->builder()->distinct();

        if (!is_null($sort)) {
            $builder->orderBy($sort, $sortDir);
        }

        if ($sort !== 'user_name') {
            $builder->orderBy('user_name');
        }

        if ($sort !== 'user_email') {
            $builder->orderBy('user_email');
        }

        return $this;
    }

    public function getByFilters(?string $sort, ?string $sortDir, string $search) : UserModel
    {
        $builder = $this->getData($sort, $sortDir);
        if ($search !== "") {
            $builder->orLike('user_name', $search)
                    ->orLike('user_email', $search);
        }
        return $this;
    }

    public function getId(?string $email) : ?int
    {
        return $this->getByEmail($email)->id ?? null;
    }

    public function getByEmail(?string $email) : ?User
    {
        if ($email === null) {
            return null;
        }
        return $this->builder()
                    ->select('*')
                    ->where('user_email', $email)
                    ->get()
                    ->getCustomRowObject(0, User::class);
    }

    public function deleteEmail(string $email) : void
    {
        $this->builder()
             ->delete(['user_email' => $email]);
    }

    protected function hashPassword(array $data) : array
    {
        if (!isset($data['data']['user_password'])) {
            return $data;
        }

        $data['data']['user_password'] = password_hash($data['data']['user_password'], PASSWORD_DEFAULT);
        unset($data['data']['password']);

        return $data;
    }
}

<?php declare(strict_types=1);

namespace App\Persistence\Repositories;

use App\Persistence\Entities\User;
use App\Persistence\Models\UserModel;

final class UsersRepository
{
    use Traits\Find;
    use Traits\Filter;
    use Traits\Save;
    use Traits\Delete;

    public function __construct(
        private readonly UserModel $model
    ) {}

    public function ValidateUser(User $user): bool
    {
        return $this
            ->model
            ->where('email', $user->email)
            ->where('password', password_hash($user->password, PASSWORD_DEFAULT))
            ->limit(1)
            ->countAllResults() > 0;
    }

    public function findFiltered(string $search, array $filters, string $sort, string $sortDirection): array
    {
        $sorters = ['name', 'email', 'created_at', 'updated_at'];

        return $this
            ->filtered($filters)
            ->like('name', $search)
            ->findAll();
    }
}

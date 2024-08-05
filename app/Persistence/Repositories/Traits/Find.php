<?php declare(strict_types=1);

namespace App\Persistence\Repositories\Traits;

/**
 * @template T of Entity
 */
trait Find
{
    public function find(int $id): ?object
    {
        return $this->model->find($id);
    }

    public function findAll(): array
    {
        return $this->model->findAll();
    }

    public function findPage(int $page, int $perPage): array
    {
        return $this->model->paginate($perPage, 'default', $page);
    }
}

<?php declare(strict_types=1);

namespace App\Persistence\Repositories\Traits;

/**
 * @template T of Entity
 */
trait Delete
{
    /**
     * @param T $entity
     */
    public function delete($entity): bool
    {
        /** @var \CodeIgniter\Model */
        $model = $this->model;
        $id = $model->getIdValue($entity);
        if ($id !== null && $id !== 0 && $id !== '' && $id !== []) {
            return $model->builder()->delete($id);
        }
        return false;
    }
}

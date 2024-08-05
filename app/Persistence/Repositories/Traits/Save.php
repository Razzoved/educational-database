<?php declare(strict_types=1);

namespace App\Persistence\Repositories\Traits;

/**
 * @template T of Entity
 */
trait Save
{
    /**
     * @param T $entity
     */
    public function save($entity): bool|int
    {
        /** @var \CodeIgniter\Model */
        $model = $this->model;
        $id = $model->getIdValue($entity);
        $method = $id !== null &&
            $id !== 0 &&
            $id !== '' &&
            $model->find($id) !== null
                ? fn($e) => $model->update($id, $e)
                : fn($e) => $model->insert($e, returnID: true);
        return $method($entity);
    }
}

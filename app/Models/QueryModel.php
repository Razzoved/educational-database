<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

/**
 * Abstract extension of CI4 Model. Adds a setupQuery call right
 * before every find/findAll/paginate to hide sorting and filtering
 * query building logic from the controllers.
 *
 * Controllers only need to provide the query parameters.
 *
 * Example data:
 * - string $search
 * - string $sortBy
 * - string $sortDir
 *
 * Each model may support a different data set.
 *
 * @author Jan Martinek
 */
abstract class QueryModel extends Model
{
    public function find($id = null, array $data = [])
    {
        $this->beforeQuery($data);
        return parent::find($id);
    }

    public function findAll(int $limit = 0, int $offset = 0, array $data = [])
    {
        $this->beforeQuery($data);
        return parent::findAll();
    }

    public function paginate(
        ?int $perPage = null,
        string $group = 'default',
        ?int $page = 1,
        int $segment = 0,
        array $data = []
    ) {
        $this->beforeQuery($data);
        return parent::paginate($perPage, $group, $page, $segment);
    }

    abstract protected function beforeQuery(array $data = []): self;

    protected function sortBy(array &$data, ?string $default = null)
    {
        if (!isset($data['sortBy']) || $data['sortBy'] === '' || (
            $data['sortBy'] !== $this->primaryKey &&
            $data['sortBy'] !== $this->updatedField &&
            $data['sortBy'] !== $this->createdField &&
            $data['sortBy'] !== $this->deletedField &&
            !in_array($data['sortBy'], $this->allowedFields)
        )) {
            $data['sortBy'] = $default ?? $this->primaryKey;
        }
    }

    protected function sortDir(array &$data, string $default = 'DESC')
    {
        $data['sortDir'] = isset($data['sortDir'])
            ? strtoupper($data['sortDir'])
            : '';
        if ($data['sortDir'] !== 'ASC' || $data['sortDir'] !== 'DESC') {
            $data['sortDir'] = $default;
        }
    }
}

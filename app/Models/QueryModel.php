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
    abstract protected function beforeQuery(array $data = []): self;
    
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

    /* -----------------------------------------------------------------------
    *                           PREFIXED METHODS
    * --------------------------------------------------------------------- */
 
    public function where(
        string $field,
        $value = null,
        $escape = null,
        $prefix = null
    ): self {
        $prefix = $prefix ?? $this->table;
        if ($prefix !== '') {
            $prefix .= '.';
        }
        return parent::where($prefix . $field, $value, $escape);
    }

    public function orLike(
        string $field,
        string $match = '',
        string $side = 'both',
        $escape = null,
        $insensitiveSearch = false,
        $prefix = null
    ): self {
        $prefix = $prefix ?? "{$this->db->prefixTable($this->table)}";
        if ($prefix !== '') {
            $prefix .= '.';
        }
        return parent::orLike($prefix . $field, $match, $side, $escape, $insensitiveSearch);
    }

    public function orderBy(
        string $field,
        string $direction = '',
        $escape = null,
        $prefix = null
    ): self {
        $prefix = $prefix ?? $this->table;
        if ($prefix !== '') {
            $prefix .= '.';
        }
        return parent::orderBy($prefix . $field, $direction, $escape);
    }

    /* -----------------------------------------------------------------------
    *                              HELPERS
    * --------------------------------------------------------------------- */
    
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

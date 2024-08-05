<?php declare(strict_types=1);

namespace App\Persistence\Repositories\Traits;

use App\Persistence\Repositories\Direction;

trait Filter
{
    private function filtered(array $filters)
    {
        /** @var \CodeIgniter\Model */
        $model = $this->model;
        foreach ($filters as $key => $value) {
            if (in_array($key, $model->allowedFields)) {
                $model->where($key, $value);
            }
        }
        return $model;
    }

    private function sorted(array $sort)
    {
        /** @var \CodeIgniter\Database\BaseBuilder */
        $builder = $this->model->builder();
        foreach (array_unique($sort) as $key => $direction) {
            if (
                in_array($key, $this->model->allowedFields) &&
                ($dir = Direction::tryFrom(strtolower($direction))) !== null
            ) {
                $builder->orderBy($key, $dir->value);
            }
        }
        return $builder;
    }
}

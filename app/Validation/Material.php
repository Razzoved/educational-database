<?php declare(strict_types = 1);

namespace App\Validation;

use App\Entities\Cast\StatusCast;

class Material
{
    public function validStatus(?string $status) : bool
    {
        return StatusCast::isValid($status) || StatusCast::isValidIndex(status);
    }
}

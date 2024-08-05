<?php declare(strict_types=1);

namespace App\Persistence\Repositories;

final class MaterialsRepository
{
    use Traits\Find;
    use Traits\Filter;
    use Traits\Save;
    use Traits\SoftDelete;
}

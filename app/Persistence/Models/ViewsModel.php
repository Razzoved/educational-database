<?php declare(strict_types=1);

namespace App\Persistence\Models;

use App\Persistence\Entities\View;
use CodeIgniter\Model;

/**
 * This model handles the operations over the views table. The table
 * stores the viewcount information for each material on daily basis
 * (ie. increments the views for current day, or creates a new record).
 *
 * The model also provides a retrieval method for the latest most
 * viewed materials.
 *
 * @author Jan Martinek
 */
class ViewsModel extends Model
{
    protected $table = 'views';
    protected $primaryKey = View::ID;

    protected $allowedFields = [
        View::PARENT,
        View::COUNT,
    ];

    protected $useAutoIncrement = true;
    protected $useTimestamps = true;
    protected $useSoftDeletes = View::DELETED !== '';
    protected $dateFormat = 'date';
    protected $createdField = View::CREATED;
    protected $updatedField = View::UPDATED;
    protected $deletedField = View::DELETED;
    protected $returnType = View::class;
}

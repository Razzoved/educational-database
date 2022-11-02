<?php declare(strict_types = 1);

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Property extends Entity
{
    protected $attributes = [
        'property_id'    => null,
        'property_tag'   => null,
        'property_value' => null,
    ];

    protected $casts = [
        'property_id'  => 'int',
        'property_tag' => 'string',
    ];

    public function valueToLink() : string
    {
        return "<form method='post' name='$this->property_tag[$this->property_value]' href='/'>"
            . $this->property_value
            . '</form>';
    }
}

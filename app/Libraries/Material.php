<?php declare(strict_types = 1);

namespace App\Libraries;

use App\Entities\Material as EntitiesMaterial;

class Material
{
    public function toCard(EntitiesMaterial $material) : string
    {
        return view('components/material_as_card', ['material' => $material]);
    }
}
<?php declare(strict_types = 1);

namespace App\Controllers\Admin;

use App\Controllers\Material as ControllersMaterial;

class Material extends ControllersMaterial
{
    public function index() : string
    {
        $data = [
            'meta_title' => 'Administration - Materials',
            'title'      => 'Materials',
            'filters'    => $this->materialProperties->getUsedProperties(false),
            'materials'  => $this->getMaterials(current_url(), Config::PAGE_SIZE),
            'pager'      => $this->materials->pager,
            'activePage' => 'materials',
        ];
        return view(Config::VIEW . 'material/table', $data);
    }
}

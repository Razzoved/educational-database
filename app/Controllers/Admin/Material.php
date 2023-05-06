<?php declare(strict_types = 1);

namespace App\Controllers\Admin;

use App\Controllers\Material as ControllersMaterial;

class Material extends ControllersMaterial
{
    public function index() : string
    {
        return $this->view('material/table', [
            'meta_title' => 'Administration - Materials',
            'title'      => 'Materials',
            'options'    => $this->getOptions(),
            'filters'    => $this->materialProperties->getUsed(),
            'materials'  => $this->getMaterials(ADMIN_PAGE_SIZE),
            'pager'      => $this->materials->pager,
            'activePage' => 'materials',
        ]);
    }
}

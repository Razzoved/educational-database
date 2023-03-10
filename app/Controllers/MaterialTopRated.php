<?php declare(strict_types = 1);

namespace App\Controllers;

use App\Models\MaterialModel;

class MaterialTopRated extends Material
{
    public function index() : string
    {
        $data = [
            'meta_title' => 'Materials - top rated',
            'title'      => 'Materials - top rated',
            'filters'    => $this->materialProperties->getUsedProperties(session('isLoggedIn') ?? false),
            'materials'  => $this->getMaterials(current_url()),
            'pager'      => $this->materials->pager,
            'activePage' => 'top-rated',
        ];
        return view('material_multiple', $data);
    }

    protected function loadMaterials() : MaterialModel
    {
        $sort = 'rating';
        $sortDir = 'DESC';
        $search = $this->request->getPost('search') ?? "";
        $filters = $this->request->getPost('filters') ?? [];

        return ($search !== "" || $filters !== [])
            ? $this->materials->getByFilters($sort, $sortDir, $search, $filters)
            : $this->materials->getData($sort, $sortDir);
    }
}

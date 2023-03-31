<?php declare(strict_types = 1);

namespace App\Controllers;

use App\Models\MaterialModel;

class MaterialTopRated extends Material
{
    public function index() : string
    {
        $data = [
            'meta_title' => 'Materials - top rated',
            'title'      => 'Top rated materials',
            'filters'    => $this->materialProperties->getUsedProperties(session('isLoggedIn') ?? false),
            'options'    => $this->getOptions(),
            'materials'  => $this->getMaterials(),
            'pager'      => $this->materials->pager,
            'activePage' => 'top-rated',
        ];
        return view('material_multiple', $data);
    }

    protected function loadMaterials() : MaterialModel
    {
        $sort = 'rating';
        $sortDir = 'DESC';
        $search = $this->request->getGetPost('search') ?? "";
        $filters = \App\Libraries\Property::getFilters($this->request->getGetPost() ?? []);

        return ($search !== "" || $filters !== [])
            ? $this->materials->getByFilters($sort, $sortDir, $search, $filters)
            : $this->materials->getData($sort, $sortDir);
    }
}

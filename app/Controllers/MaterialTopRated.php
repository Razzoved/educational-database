<?php declare(strict_types = 1);

namespace App\Controllers;

class MaterialTopRated extends Material
{
    public function index() : string
    {
        $data = [
            'meta_title' => 'Materials - top rated',
            'title'      => 'Top rated materials',
            'filters'    => $this->materialProperties->getUsed(),
            'options'    => $this->getOptions(),
            'materials'  => $this->getMaterials(),
            'pager'      => $this->materials->pager,
            'activePage' => 'top-rated',
        ];
        return view('material_multiple', $data);
    }

    protected function getMaterials(int $perPage = 10) : array
    {
        $uri = new \CodeIgniter\HTTP\URI(current_url());
        return $this->materials->getPage(
            $uri->getTotalSegments(),
            [
                'filters'   => \App\Libraries\Property::getFilters($this->request->getGetPost() ?? []),
                'search'    => $this->request->getGetPost('search'),
                'sort'      => 'rating',
                'sortDir'   => 'DESC',
            ],
            $perPage
        );
    }
}

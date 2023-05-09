<?php declare(strict_types = 1);

namespace App\Controllers;

class MaterialTopRated extends Material
{
    public function index() : string
    {
        return $this->view('material/all', [
            'meta_title' => 'Materials - top rated',
            'title'      => 'Top rated materials',
            'filters'    => $this->materialProperties->getUsed(),
            'options'    => $this->getOptions(),
            'materials'  => $this->getMaterials(),
            'pager'      => $this->materials->pager,
        ]);
    }

    protected function getMaterials(int $perPage = 10) : array
    {
        return $this->materials->getPage(
            (int) $this->request->getGetPost('page') ?? 1,
            [
                'filters'   => \App\Libraries\Property::getFilters($this->request),
                'search'    => $this->request->getGetPost('search'),
                'sort'      => 'rating',
                'sortDir'   => 'DESC',
            ],
            $perPage
        );
    }
}

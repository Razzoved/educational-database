<?php declare(strict_types = 1);

namespace App\Controllers;

class MaterialMostViewed extends Material
{
    public function index() : string
    {
        $data = [
            'meta_title' => 'Materials - monthly views',
            'title'      => 'Most viewed materials <em>from past 30 days</em>',
            'filters'    => [],
            'options'    => $this->getOptions(),
            'materials'  => $this->views->getTopMaterials(30),
            'pager'      => null,
            'activePage' => 'most-viewed',
        ];
        return view('material_views', $data);
    }
}

<?php declare(strict_types = 1);

namespace App\Controllers;

use App\Models\ViewsModel;

class MaterialMostViewed extends Material
{
    public function index() : string
    {
        $materials = model(ViewsModel::class)->getTopMaterials(30, $this->request->getGet('search') ?? "");

        return $this->view('material/all', [
            'meta_title' => 'Materials - monthly views',
            'title'      => 'Most viewed materials <em>from past 30 days</em>',
            'filters'    => [],
            'options'    => array_map(function($m) { return $m->title; }, $materials),
            'materials'  => $materials,
            'pager'      => null,
            'activePage' => 'most-viewed',
        ]);
    }
}

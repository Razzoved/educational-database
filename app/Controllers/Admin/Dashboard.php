<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models as M;
use App\Entities\Material as EntitiesMaterial;

class Dashboard extends DefaultController
{
    protected const COUNT_TOP = 10;
    protected const COUNT_RECENT = 10;

    protected $views;
    protected $materials;

    protected function ready()
    {
        $this->views = model(M\ViewsModel::class);
        $this->materials = model(M\MaterialModel::class);
    }

    public function index(): string
    {
        $this->cachePage(60);

        $data = [
            'meta_title'      => 'Administration - Dashboard',
            'viewsHistory'    => $this->views->getDailyTotals(),
            'materials'       => $this->views->getTopMaterials(self::COUNT_TOP, "", 30),
            'materialsTotal'  => $this->materials->findAll(self::COUNT_TOP, 0, ['sortBy' => 'views', 'sortDir' => 'DESC']),
            'editors'         => $this->materials->getBlame(),
            'recentPublished' => $this->materials->findAll(self::COUNT_RECENT, 0, ['sortBy' => 'published_at', 'sortDir' => 'DESC']),
            'recentUpdated'   => $this->materials->findAll(self::COUNT_RECENT, 0, ['sortBy' => 'updated_at', 'sortDir' => 'DESC']),
            'pageClass'       => ['dashboard'],
            'hasSidebar'      => true
        ];

        $data['viewsTotal'] = array_reduce(
            $this->views->findAll(),
            function ($prev, $mat) {
                $mat->views += $prev->views;
                return $mat;
            },
            new EntitiesMaterial()
        )->views;

        return $this->view('dashboard', $data);
    }
}

<?php declare(strict_types = 1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Entities\Material as EntitiesMaterial;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Dashboard extends BaseController
{
    protected const COUNT_TOP = 10;
    protected const COUNT_RECENT = 10;

    protected $views;
    protected $materials;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->views = model(ViewsModel::class);
        $this->materials = model(MaterialModel::class);
    }

    public function index() : string
    {
        $data = [
            'meta_title'        => 'Administration - dashboard',
            'activePage'        => 'dashboard',
            'viewsHistory'      => $this->views->getDailyTotals(),
            'materials'         => $this->views->getTopMaterials(self::COUNT_TOP, "", 30),
            'materialsTotal'    => $this->materials->getArray(['sort' => 'views', 'sortDir' => 'DESC'], self::COUNT_TOP),
            'editors'           => $this->materials->getBlame(),
            'recentPublished'   => $this->materials->getArray(['sort' => 'published_at', 'sortDir' => 'DESC'], self::COUNT_RECENT),
            'recentUpdated'     => $this->materials->getArray(['sort' => 'updated_at', 'sortDir' => 'DESC'], self::COUNT_RECENT),
        ];

        $data['viewsTotal'] = array_reduce(
            $this->views->findAll(),
            function ($prev, $mat) {
                $mat->views += $prev->views; return $mat;
            },
            new EntitiesMaterial()
        )->views;

        return $this->view('dashboard', $data);
    }
}

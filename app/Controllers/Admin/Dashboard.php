<?php declare(strict_types = 1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Entities\Material as EntitiesMaterial;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Dashboard extends BaseController
{
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
            'meta_title'     => 'Administration - dashboard',
            'activePage'     => 'dashboard',
            'viewsTotal'     => array_sum(array_column($this->views->findAll(), 'material_views')),
            'viewsHistory'   => $this->views->getDailyTotals(),
            'materials'      => $this->views->getTopMaterials(5, 30),
            'materialsTotal' => $this->materials->getArray(['sort' => 'views', 'sortDir' => 'DESC'], 5),
            'contributors'   => $this->materials->getContributors(),
            'recentNew'      => $this->materials->getArray(['sort' => 'created_at', 'sortDir' => 'DESC'], 5),
            'recentUpdated'  => $this->materials->getArray(['sort' => 'updated_at', 'sortDir' => 'DESC'], 5),
        ];
        return view(Config::VIEW . 'dashboard', $data);
    }
}

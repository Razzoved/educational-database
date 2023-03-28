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
            'meta_title'    => 'Administration - dashboard',
            'activePage'    => 'dashboard',
            'viewsTotal'    => array_sum(array_column($this->views->findAll(), 'material_views')),
            'viewsHistory'  => $this->views->getDailyTotals(),
            'materials'     => $this->views->getTopMaterials(8),
            'contributors'  => $this->materials->getContributors(),
            'recentNew'     => $this->materials->getData('created_at', 'DESC')->get(5)->getCustomResultObject(EntitiesMaterial::class),
            'recentUpdated' => $this->materials->getData('updated_at', 'DESC')->get(5)->getCustomResultObject(EntitiesMaterial::class),
        ];
        return view(Config::VIEW . 'dashboard', $data);
    }
}

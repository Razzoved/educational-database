<?php declare(strict_types = 1);

namespace App\Controllers\Admin;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Models\MaterialModel;

class Material extends AdminController
{
    private MaterialModel $materialModel;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->materialModel = model(MaterialModel::class);
        $this->all = "material_multiple";
        $this->single = "material_single";
        // E.g.: $this->session = \Config\Services::session();
    }

    public function index(int $page) : string
    {
        return parent::viewMultiple(
            'Admin all material',
            $this->materialModel->findAll($this->pageSize, $page * $this->pageSize)
        );
    }

    // public function new() : string
    // {
    // }

    public function edit($id) : string
    {
        return parent::viewOne(
            'Admin single material',
            $this->materialModel->find($id)
        );
    }

    // public function delete($id) : string
    // {
    // }
}

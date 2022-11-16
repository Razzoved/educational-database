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

        // E.g.: $this->session = \Config\Services::session();
    }

    public function index(int $page) : string
    {
        return parent::index(
            'Materials editor',
            $this->materialModel->findAll($this->$pageSize, $page * $this->$pageSize)
        );
    }

    // public function new() : string
    // {
    // }

    // public function edit($id) : string
    // {
    // }

    // public function delete($id) : string
    // {
    // }
}

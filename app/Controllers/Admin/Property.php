<?php declare(strict_types = 1);

namespace App\Controllers\Admin;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Models\PropertyModel;

class Property extends AdminController
{
    private PropertyModel $propertyModel;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->propertyModel = model(PropertyModel::class);

        // E.g.: $this->session = \Config\Services::session();
    }

    // public function index(int $page) : string
    // {
    // }

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

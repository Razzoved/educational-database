<?php declare(strict_types = 1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Dashboard extends BaseController
{
    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }

    public function index() : string
    {
        $data = [
            'meta_title' => 'Administration - dashboard',
            'activePage' => 'dashboard',
        ];

        return view(Config::VIEW . 'dashboard', $data);
    }
}

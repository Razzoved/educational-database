<?php declare(strict_types = 1);

namespace App\Controllers\Admin;

use CodeIgniter\Entity\Entity;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;

class AdminController extends \App\Controllers\BaseController
{
    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->session = \Config\Services::session();
    }

    protected function viewAll(string $title, array $entities) : string
    {
        if (!isset($title)) throw new RuntimeException("page title not set");
        if (!isset($entities)) throw new RuntimeException("entities not set");

        $data = [
            'title'    => $title,
            'entities' => $entities,
            'isEmpty'  => ($entities == []),
        ];

        return view('admin_all', $data);
    }

    protected function viewOne(Entity $entity) : string
    {
        if (!isset($entity)) throw new RuntimeException("page title not set");

        $data = [

        ];

        return view('admin_one', $data);
    }

    protected function viewForm(Entity $entity) : string
    {
        if (!isset($entity)) throw new RuntimeException("page title not set");

        $data = [

        ];

        return view('admin_form', $data);
    }
}

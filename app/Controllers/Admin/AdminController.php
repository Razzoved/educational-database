<?php declare(strict_types = 1);

namespace App\Controllers\Admin;

use CodeIgniter\Entity\Entity;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;

class AdminController extends \App\Controllers\BaseController
{
    protected $all;
    protected $single;

    protected $pageSize = 100;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->session = \Config\Services::session();
    }

    protected function viewMultiple(string $title, array $entities, array $otherData) : string
    {
        if (!isset($title)) throw new RuntimeException("page title not set");
        if (!isset($entities)) throw new RuntimeException("entities not set");

        $data = [
            'title'     => $title,
            'entities'  => $entities,
            'otherData' => $otherData,
        ];

        return view('admin/' . $this->all, $data);
    }

    protected function viewOne(string $title, Entity $entity) : string
    {
        if (!isset($title)) throw new RuntimeException("page title not set");
        if (!isset($entity)) throw new RuntimeException("page title not set");

        $data = [
            'title'  => $title,
            'entity' => $entity,
        ];

        return view('admin/' . $this->single, $data);
    }
}

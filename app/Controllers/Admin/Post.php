<?php declare(strict_types = 1);

namespace App\Controllers\Admin;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Models\MaterialModel;
use App\Models\PostModel;

class Post extends AdminController
{
    private PostModel $postModel;
    private MaterialModel $materialModel;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->postModel = new PostModel();
        $this->materialModel = new MaterialModel();

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

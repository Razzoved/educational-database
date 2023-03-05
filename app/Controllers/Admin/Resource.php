<?php declare(strict_types = 1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Models\ResourceModel;
use CodeIgniter\HTTP\Response;

class Resource extends BaseController
{
    private ResourceModel $resources;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->resources = model(ResourceModel::class);
    }

    public function index() : string
    {
        $data = [
            'meta_title' => 'Administration - unused resources',
            'title' => 'Resources',
            'activePage' => 'files',
        ];

        return view(Config::VIEW . 'resource/table', $data);
    }

    public function upload() : void
    {
        $views = array();

        foreach ($this->request->getFiles() as $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                $name = $file->getName();
                $views[$name] = view(Config::VIEW . 'file_template', [
                    'id' => $this->request->getPostGet('id'),
                    'path' => 'writable/uploads/' . $file->store(),
                    'value' => $name,
                ]);
            }
        }

        if ($views === array()) {
            $this->response->setStatusCode(Response::HTTP_NOT_ACCEPTABLE, "No files saved");
            return;
        }

        echo json_encode($views);
    }

    public function move() : void
    {
        helper('file');
        $moved = array();

        $target = $this->request->getPost('target');
        $files = $this->request->getPost('files');

        if ($target === null) {
            $this->response->setStatusCode(Response::HTTP_NOT_ACCEPTABLE, "Moving requires a target");
            return;
        }

        if ($files === null) {
            $this->response->setStatusCode(Response::HTTP_NOT_ACCEPTABLE, "No files given to be moved");
            return;
        }

        foreach ($files as $file) {
            $splFile = new \CodeIgniter\Files\File($file['path']);
            if ($splFile->getRealPath()) {
                $moved[] = $splFile->move($target, $file['name']);
            }
        }

        if ($moved === array()) {
            $this->response->setStatusCode(Response::HTTP_NOT_ACCEPTABLE, "No files moved");
            return;
        }

        echo json_encode($moved);
    }

    public function delete() : void
    {
        $removed = array();

        foreach ($this->request->getFiles() as $file) {
            if ($file->isVaid() && !$file->hasMoved()) {
                if (unlink($file->getPath())) $removed[] = $file->getName();
            }
        }

        if ($removed === array()) {
            $this->response->setStatusCode(Response::HTTP_NOT_ACCEPTABLE, "No files deleted");
            return;
        }

        echo json_encode($removed);
    }
}

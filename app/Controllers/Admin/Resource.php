<?php declare(strict_types = 1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\Resources;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Resource extends BaseController
{
    private Resources $resourceLibrary;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->resourceLibrary = new Resources($this->response);
    }

    public function index() : string
    {
        helper('filesystem');

        $targets = array();
        $materials = model(MaterialModel::class)->getData(null, null, false)
                                                ->get()
                                                ->getCustomResultObject(\App\Entities\Material::class);
        foreach ($materials as $key => $material) {
            $targets[$material->id] = $material->title;
        }

        $data = [
            'meta_title' => 'Administration - unused files',
            'title'      => 'Unused files',
            'resources'  => $this->resourceLibrary->getUnused(),
            'targets'    => $targets,
            'activePage' => 'files',
        ];

        return view(Config::VIEW . 'resource/table', $data);
    }

    public function uploadThumbnail() : void
    {
        $resource = $this->resourceLibrary->store($this->request->getFile('file'));
        if ($resource === null) {
            $this->resourceLibrary->echoError('Thumbnail could not be stored');
            return;
        }
        echo json_encode($resource->tmp_path);
    }

    public function upload() : void
    {
        $views = array();

        foreach ($this->request->getFiles() as $file) {
            if (($resource = $this->resourceLibrary->store($file)) !== null) {
                $views[$resource->path] = view(Config::VIEW . 'file_template', [
                    'id'    => $this->request->getPostGet('id'),
                    'path'  => $resource->tmp_path,
                    'value' => $resource->path,
                ]);
            }
        }

        if ($views === array()) {
            $this->resourceLibrary->echoError('No files were uploaded');
            return;
        }

        echo json_encode($views);
    }

    public function assign(int $materialId, bool $replaceThumbnail = false) : void
    {
        $path = $this->request->getPost('tmp_path');
        if ($path === null) {
            $this->resourceLibrary->echoError('No file present for assigning');
            return;
        }

        $success = $this->resourceLibrary->assign($materialId, new \App\Entities\Resource([
            'path'     => $path,
            'type'     => $replaceThumbnail ? 'thumbnail' : 'file',
        ]));

        if (!$success) {
            $this->resourceLibrary->echoError('<strong>Unexpected error</strong>: Assignment to material failed, try again later.');
            return;
        }

        echo json_encode($path);
    }

    public function delete() : void
    {
        $success = $this->resourceLibrary->delete(new \App\Entities\Resource([
            'id' => $this->request->getPost('id'),
            'path' => $this->request->getPost('path')
        ]));

        if (!$success) {
            $this->resourceLibrary->echoError('<strong>Unexpected error</strong>: Attemp to delete resource failed, try again later.');
            return;
        }

        echo json_encode($this->request->getPost('id') ?? $this->request->getPost('path'));
    }
}

<?php declare(strict_types = 1);

namespace App\Controllers\Admin;

use App\Entities\Resource as EntitiesResource;
use App\Libraries\Resources;
use App\Models\ResourceModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Psr\Log\LoggerInterface;

class Resource extends ResponseController
{
    protected Resources $resourceLibrary;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->resourceLibrary = new Resources($this->response);
    }

    public function index() : string
    {
        helper('filesystem');

        $targets = array();
        $materials = model(MaterialModel::class)->getArray(['callbacks' => false]);

        foreach ($materials as $material) {
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

    /** ----------------------------------------------------------------------
     *                           AJAX HANDLERS
     *  ------------------------------------------------------------------- */

    public function upload() : Response
    {
        $file = $this->request->getFile('file');

        $resource = $this->resourceLibrary->store($file);
        if (!$resource) {
            return $this->response->setStatusCode(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'No files were uploaded'
            );
        }

        return $this->response->setJSON($resource);
    }

    public function uploadImage() : Response
    {
        $rules = ['file' => 'is_image[file]'];
        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(
                Response::HTTP_BAD_REQUEST,
                'File is not an image'
            );
        }
        return $this->upload();
    }

    public function assign(int $materialId) : Response
    {
        $resource = new EntitiesResource($this->request->getPost());

        if (!$resource->path) {
            return $this->response->setStatusCode(
                Response::HTTP_BAD_REQUEST,
                'Cannot assign a non-existent resource!'
            );
        }

        if (!$this->resourceLibrary->assign($materialId, $resource)) {
            return $this->response->setStatusCode(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Could not assign resource, try again later!'
            );
        }

        return $this->response->setJSON($resource);
    }

    public function delete(int $id) : Response
    {
        return $this->doDelete(
            $id,
            model(ResourceModel::class)->find,
            function ($e) { if (!$this->resourceLibrary->delete($e)) throw new Exception('deletion failed'); },
            'resource'
        );
    }

    public function deleteUnused(string ...$path) : Response
    {
        if (sizeof($path) < 4) {
            return $this->response->setStatusCode(
                Response::HTTP_BAD_REQUEST,
                'Missing leading segments'
            );
        }

        $prefix = $path[0] . '/' . $path[1] . '/' . $path[2] . '/';

        if ($prefix !== TEMP_PREFIX . UNUSED) {
            return $this->response->setStatusCode(
                Response::HTTP_BAD_REQUEST,
                'Invalid path prefix.'
            );
        }

        $path = implode('/', $path);
        if (strpos($path, '../') !== false) {
            return $this->response->setStatusCode(
                Response::HTTP_BAD_REQUEST,
                'Path cannot contain "../" (cannot go up)!'
            );
        }

        if ($path && !unlink(ROOTPATH . $path)) {
            return $this->response->setStatusCode(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Could not delete file: ' . $path . '<br>Please try again later!'
            );
        }

        return $this->response->setJSON([ 'id' => $path ]);
    }
}

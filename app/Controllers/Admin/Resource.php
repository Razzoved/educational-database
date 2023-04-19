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

    public function upload() : ResponseInterface
    {
        $resources = array();

        foreach ($this->request->getFiles() as $file) {
            $resource = $this->resourceLibrary->store($file);
            if ($resource) $resources[] = $resource;
        }

        if ($resources === array()) {
            return $this->response->setStatusCode(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'No files were uploaded'
            )->send();
        }

        echo json_encode($resources);
    }

    public function assign(int $materialId) : ResponseInterface
    {
        $resource = new EntitiesResource($this->request->getPost());

        if (!$resource->path) {
            return $this->response->setStatusCode(
                Response::HTTP_BAD_REQUEST,
                'Cannot assign a non-existent resource!'
            )->send();
        }

        if (!$this->resourceLibrary->assign($materialId, $resource)) {
            return $this->response->setStatusCode(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Could not assign resource, try again later!'
            )->send();
        }

        echo json_encode($resource);
    }

    public function delete(int $id) : ResponseInterface
    {
        return $this->doDelete(
            $id,
            model(ResourceModel::class)->find,
            function ($e) { if (!$this->resourceLibrary->delete($e)) throw new Exception('deletion failed'); },
            'resource'
        );
    }
}

<?php declare(strict_types = 1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\Resources;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Models\ResourceModel;
use CodeIgniter\HTTP\Response;

use function PHPUnit\Framework\isNan;

class Resource extends BaseController
{
    private ResourceModel $resources;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->resources = model(ResourceModel::class);
        $this->resourceLibrary = new Resources();
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
        helper('file');

        $name = $this->request->getPost('name');
        if (!$name || $name === "") {
            $name = $this->request->getPost('tmp_name');
        }

        $path = $this->request->getPost('tmp_path');
        if ($path === null) {
            $this->resourceLibrary->echoError('No file present for assigning');
            return;
        }

        $file = new \CodeIgniter\Files\File(ROOTPATH . $path);
        if ($file->getRealPath()) {
            if ($replaceThumbnail) {
                $resource = ($this->resources->getThumbnail($materialId));
                if ($resource !== array()) {
                    $_POST['id'] = $resource[0]->id;
                    $this->delete(false);
                }
            }
            $moved = $file->move(PUPPATH . $materialId, $name);
            $this->resources->insert([
                'material_id' => $materialId,
                'resource_path' => $moved->getFilename(),
                'resource_type' => $replaceThumbnail ? 'thumbnail' : 'file',
            ]);
        } else {
            $this->resourceLibrary->echoError('File does not exist');
            return;
        }

        echo json_encode($path);
    }

    public function delete(bool $doEcho = true) : void
    {
        $resourceId = $this->request->getPost('id');
        $resourcePath = $this->request->getPost('path');

        if (!$resourceId && $resourcePath) {
            $this->deleteUnsaved($resourcePath, true);
            return;
        }

        $resource = $this->resources->find($resourceId);
        if (!$resource) {
            $this->resourceLibrary->echoError('Resource id is not present in database');
            return;
        }

        $this->resources->delete($resourceId);
        $this->deleteUnsaved($resource->getPath(false), false);

        if ($doEcho) {
            echo json_encode($resourceId);
        }
    }

    public function deleteUnsaved(string $path, bool $doEcho = true) : void
    {
        if (!$path || !unlink(ROOTPATH . $path)) {
            $this->resourceLibrary->echoError('Could not delete file');
            return;
        }

        if ($doEcho) {
            echo json_encode($path);
        }
    }
}

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

        $unused = array();
        $this->getUnusedArray(directory_map(WRITEPATH . 'uploads/'), $unused);

        $targets = array();
        $materials = model(MaterialModel::class)->getData(null, null, false)
                                                ->get()
                                                ->getCustomResultObject(\App\Entities\Material::class);
        foreach ($materials as $key => $material) {
            $targets[$material->id] = $material->title;
        }

        $data = [
            'meta_title' => 'Administration - unused files',
            'title' => 'Unused files',
            'resources' => $unused,
            'targets' => $targets,
            'activePage' => 'files',
        ];

        return view(Config::VIEW . 'resource/table', $data);
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
            $this->echoError('No files were uploaded');
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
            $this->echoError('No file present for assigning');
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
            $this->echoError('File does not exist');
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
            $this->echoError('Resource id is not present in database');
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
            $this->echoError('Could not delete file');
            return;
        }

        if ($doEcho) {
            echo json_encode($path);
        }
    }

    private function getUnusedArray(array $source, array &$result, string $path = 'writable/uploads') : void
    {
        foreach ($source as $key => $value) {
            $key = (string) $key;

            $newPath = $path;
            if (substr($key, -1) === '\\') {
                $newPath .= '/' . rtrim((string) $key, '\\');
            }

            if (is_array($value)) {
                if ($key < date('Ymd', time())) {
                    $this->getUnusedArray($value, $result, $newPath);
                }
            } else if (substr($value, 0, 5) !== 'index') {
                $result[] = new \App\Entities\Resource([
                    'resource_path' => $newPath . '/' . $value,
                    'resource_type' => 'file'
                ]);
            }
        }
    }

    /**
     * Utility function for easier sending of errors.
     */
    private function echoError(
        string $message = "Internal server error!",
        int $errorCode = Response::HTTP_INTERNAL_SERVER_ERROR,
        string $title = "Resource manipulation"
    ) : void {
        $this->response->setStatusCode($errorCode);
        echo view('errors/error_modal', [
            'title'     => $title,
            'message'   => $message
        ]);
    }
}

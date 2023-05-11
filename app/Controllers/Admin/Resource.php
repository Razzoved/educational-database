<?php declare(strict_types = 1);

namespace App\Controllers\Admin;

use App\Entities\Resource as EntitiesResource;
use App\Libraries\Resource as ResourceLib;
use App\Models\ResourceModel;
use CodeIgniter\HTTP\Response;
use Exception;

class Resource extends ResponseController
{
    public function index() : string
    {
        helper('filesystem');

        $targets = array();
        $materials = model(MaterialModel::class)->getArray(['callbacks' => false]);

        foreach ($materials as $material) {
            $targets[$material->id] = $material->title;
        }

        return $this->view('resource/table', [
            'meta_title' => 'Administration - Files',
            'title'      => 'Unused files',
            'resources'  => ResourceLib::getUnused(),
            'targets'    => $targets,
        ]);
    }

    /** ----------------------------------------------------------------------
     *                           AJAX HANDLERS
     *  ------------------------------------------------------------------- */

    public function upload() : Response
    {
        helper('security');

        $file = $this->request->getFile('file');
        $type = self::resolveType($this->request->getPost('fileType'));

        $rules = ['file' => 'uploaded[file]'];
        if ($type === 'thumbnail') {
            $rules['file'] .= '|is_image[file]';
        }

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(
                Response::HTTP_BAD_REQUEST,
                current($this->validator->getErrors())
            );
        }

        try {
            $resource = ResourceLib::store($file);
            if ($type === 'thumbnail') {
                \Config\Services::image()
                    ->withFile(ROOTPATH . $resource->tmp_path)
                    ->fit(512, 512, 'center')
                    ->save(ROOTPATH . $resource->tmp_path);
            }
        } catch (Exception $e) {
            return $this->response->setStatusCode(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'File could not be uploaded; ' . $e->getMessage()
            );
        }

        $resource->imageURL = ResourceLib::pathToFileURL($resource->tmp_path);
        return $this->response->setJSON($resource);
    }

    public function assign() : Response
    {
        $tmpPath = $this->request->getPost('tmp_path');
        $materialId = $this->request->getPost('target');

        if (!$materialId || !is_numeric($materialId)) {
            return $this->response->setStatusCode(
                Response::HTTP_BAD_REQUEST,
                'Invalid material id!'
            );
        }

        $resource = new EntitiesResource($this->request->getPost());
        $resource->type = 'file';

        if (!$resource->tmp_path || !is_file(ROOTPATH . $resource->tmp_path)) {
            return $this->response->setStatusCode(
                Response::HTTP_BAD_REQUEST,
                'Cannot assign a non-existent resource!'
            );
        }

        try {
            ResourceLib::assign((int) $materialId, $resource);
        } catch (Exception $e) {
            return $this->response->setStatusCode(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $e->getMessage()
            );
        }

        return $this->response->setJSON(['id' => $tmpPath]);
    }

    public function delete(int $id) : Response
    {
        return $this->doDelete(
            $id,
            model(ResourceModel::class)->find,
            function ($e) { ResourceLib::delete($e); },
            'resource'
        );
    }

    public function deleteUnusedAll() : Response
    {
        $all = ResourceLib::getUnused();
        $paths = array();
        foreach ($all as $resource) {
            $this->deleteUnused($resource->path);
            $paths[] = $resource->path;
        }
        return $this->response->setJSON($paths);
    }

    public function deleteUnused(string ...$path) : Response
    {
        $prefix = TEMP_PREFIX;
        $path = implode(UNIX_SEPARATOR, $path);

        if (substr($path, 0, strlen($prefix)) !== $prefix) {
            return $this->response->setStatusCode(
                Response::HTTP_BAD_REQUEST,
                "Invalid prefix, needs to be: {$prefix}!"
            );
        }

        if (strpos($path, '..' . UNIX_SEPARATOR) !== false) {
            return $this->response->setStatusCode(
                Response::HTTP_BAD_REQUEST,
                'Path must not go up above current directory!'
            );
        }

        try {
            ResourceLib::delete(new EntitiesResource([
                'path' => $path,
                'tmp_path' => $path
            ]));
        } catch (Exception $e) {
            return $this->response->setStatusCode(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $e->getMessage()
            );
        }

        return $this->response->setJSON([ 'id' => $path ]);
    }

    /** ----------------------------------------------------------------------
     *                               HELPERS
     *  ------------------------------------------------------------------- */

    private static function resolveType($value) : string
    {
        switch ($value) {
            case 'thumbnail':
                return $value;
            default:
                return 'file';
        }
    }
}

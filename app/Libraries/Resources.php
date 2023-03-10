<?php declare(strict_types = 1);

namespace App\Libraries;

use App\Entities\Resource;
use CodeIgniter\Files\File;
use CodeIgniter\HTTP\Response;

/**
 * Library for working with files without the need to take care of database
 * records. Mainly aimed at usage inside of controllers.
 *
 * @author Jan Martinek
 */
class Resources
{
    /**
     * Stores the file while updating its data so it can be used
     * elsewhere later.
     *
     * @param File $file splfile we want to store
     */
    public function store(File $file) : Resource
    {
        helper('file');

        $date = date('Ymd', time());
        $dirPath = TEMP_PATH . $date;

        if (!is_dir($dirPath) && !mkdir($dirPath, 0777, true)) {
            return null;
        }

        if ($file->isValid() && !$file->hasMoved()) {
            $name = $file->getName();
            $resource = new Resource([
                'tmp_path'  => 'writable/uploads/' . $file->store(null, $name),
                'path'      => $name
            ]);
            if ($this->moveFile($resource, $dirPath)) {
                $resource->tmp_path = TEMP_PREFIX . $date . '/' . $resource->path;
                return $resource;
            }
        }

        return null;
    }

    /**
     * Assign a given resource to some material. If another material with
     * the same name already exists in targeted material, adds a number
     * behind assigned one.
     *
     * @param int $materialId       assign to this material
     * @param Resource $resource    what to operate on
     */
    public function assign(int $materialId, Resource $resource) : bool
    {
        helper('file');
        $model = model(ResourceModel::class);

        // cannot reassign
        if ($resource->isAssigned()) {
            return false;
        }

        // move old thumbnail to unused
        switch ($resource->type) {
            case 'thumbnail':
                $old = $model->getThumbnail($materialId);
                if ($old) $this->unassign($materialId);
            case 'file':
                break;
            default:
                return $this->saveToDatabase($resource);
        }

        // create directory if it does not exist
        if (!is_dir(SAVE_PATH . $materialId) && !mkdir(SAVE_PATH . $materialId)) {
            return false;
        }

        if (!$this->moveFile($resource, SAVE_PATH . $resource->parentId)) {
            return false;
        }

        $resource->parentId = $material->id;
        if (!$this->saveToDatabase($resource, true)) {
            return false;
        }

        return true;
    }

    /**
     * Move the given resource away from its parent. This means deletion
     * of record from DB and moving of file to unused folder.
     *
     * @param Resource $resource
     */
    public function unassign(Resource $resource) : bool
    {
        helper('file');

        // create directory if it does not exist
        if (!is_dir(UNUSED_PATH) && !mkdir(UNUSED_PATH)) {
            return false;
        }

        if (!$this->moveFile($resource, UNUSED_PATH)) {
            return !file_exists(ROOTPATH . $resource->getPath(false));
        }

        // remove from db
        try {
            if ($resource->id > 0) model(ResourceModel::class)->delete($resource->id);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Deletes a single file from the server, given by the resource.
     * If resource contains parentId, also removes the DB record.
     *
     * @param Resource $resource
     */
    public function delete(Resource $resource) : bool
    {
        // remove from db
        try {
            if ($resource->id > 0) model(ResourceModel::class)->delete($resource->id);
        } catch (Exception $e) {
            return false;
        }

        if ($resource->isAsset()) {
            return true;
        }

        // remove file
        $path = ROOTPATH . $resource->getPath(false);
        if (file_exists($path) && !unlink($path)) {
            return false;
        };
        $this->deleteSource($path);

        return true;
    }

    public function getUnused() : array
    {
        helper('filesystem');
        $result = array();
        $this->doUnusedRecursive(
            $result,
            directory_map(TEMP_PATH, 0, true),
            TEMP_PREFIX
        );
        return $result;
    }

    /**
     * Returns all unused files in array of Resource class objects.
     */
    private function doUnusedRecursive(array &$target, array $source, string $path) : void
    {
        foreach ($source as $key => $value) {
            $key = (string) $key;
            $newPath = rtrim($path, '/');

            if (is_array($value)) {
                $newPath .= '/' . rtrim((string) $key, '\\'); // dir ends with '\'
                if ($key < date('Ymd\\', time()) || $key === 'unused\\') {
                    $this->doUnusedRecursive($target, $value, $newPath);
                }
            } else if (substr($value, 0, 5) !== 'index') {
                $target[] = new \App\Entities\Resource([
                    'resource_path' => $newPath . '/' . $value,
                    'resource_type' => 'file'
                ]);
            }
        }
    }

    /**
     * Utility function for easier sending of resource manipulation errors.
     *
     * @param string $message   Error message that will be shown
     * @param int $errorCode    HTTP error to be returned
     */
    public function echoError(
        string $message = "Internal server error!",
        int $errorCode = Response::HTTP_INTERNAL_SERVER_ERROR
    ) : void
    {
        $this->response->setStatusCode($errorCode);
        echo view('errors/error_modal', [
            'title'     => "Resource manipulation",
            'message'   => $message
        ]);
    }

    /**
     * Helper function, tries to save resource into db. Can be set
     * to move file to its original location by doRevert parameter,
     * in case of failure.
     *
     * @param Resource $resource    resource to save
     * @param bool $doRevert        set to revert physical location
     */
    private function saveToDatabase(Resource $resource, bool $doRevert = false) : bool
    {
        try {
            $model->save($resource);
        } catch (Exception $e) {
            $file = new File(ROOTPATH . $resource->getPath(false));
            if ($file->getRealPath()) {
                $file->move(ROOTPATH . $resource->tmp_path);
            }
            return false;
        }
        return true;
    }

    /**
     * Helper function, removes the parent of given path if it exists
     * and is an empty directory.
     *
     * @param string $path direct child of directory we want to remove
     */
    private function deleteSource(string $path) : void
    {
        helper('filesystem');
        // get parent
        $path = explode('/', str_replace('\\', '/', $path));
        array_pop($path);
        $path = implode('/', $path);

        // remove folder
        if (is_dir($path)) {
            $dirMap = directory_map($path, 0, true);
            if (count($dirMap) === 1 && $dirMap === [0 => 'index.html']) {
                unlink($path . '/index.html');
            }
            $dirMap = directory_map($path, 0, true);
            if (count($dirMap) === 0) {
                rmdir($path);
            }
        }
    }

    /**
     * Tries to move the file to its final location (given by path).
     *
     * WARN: unclean function, changes resource tmp_path and path.
     *
     * @param Resource $resource to be moved
     * @param string $dirPath directory where to move
     */
    private function moveFile(Resource &$resource, string $dirPath) : bool
    {
        if ($resource->tmp_path === null) {
            $resource->tmp_path = $resource->getPath(false);
        }

        $path = ROOTPATH . $resource->tmp_path;

        $file = new File($path);
        if (!$file->getRealPath()) {
            return false;
        }

        if ($resource->isAsset() && !copy($path, $dirPath . DIRECTORY_SEPARATOR . $resource->path)) {
            return false;
        } else {
            $resource->path = $file->move($dirPath, $resource->path)->getBasename();
        }

        $this->deleteSource($path);
        return true;
    }
}

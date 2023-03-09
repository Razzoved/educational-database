<?php declare(strict_types = 1);

namespace App\Libraries;

use App\Entities\Resource;
use CodeIgniter\Files\File;

/**
 * Library for working with files without the need to take care of database
 * records. Mainly aimed at usage inside of controllers.
 *
 * @author Jan Martinek
 */
class Resources
{
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
        if ($resource->parentId !== null) {
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
                return false;
        }

        // create directory if it does not exist
        if (!is_dir(SAVE_PATH . $materialId) && !mkdir(SAVE_PATH . $materialId)) {
            return false;
        }

        // move the file
        $path = ROOTPATH . $resource->tmp_path;
        $file = new File($path);
        if (!$file->getRealPath()) {
            return false;
        }
        $resource->path = $file->move(SAVE_PATH . $materialId, $resource->path)->getBasename();
        $resource->parentId = $material->id;

        // save into db
        try {
            $model->save($resource);
        } catch (Exception $e) {
            $this->revertSave($resource);
            return false;
        }
        $this->deleteSource($path);
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

        // remove from db
        try {
            model(ResourceModel::class)->delete($resource->id);
        } catch (Exception $e) {
            return false;
        }

        // move the file
        $path = ROOTPATH . $resource->getPath(false);
        $file = new File($path);
        if (!$file->getRealPath()) {
            return true; // intentionall
        }
        $file->move(UNUSED_PATH, $resource->path);
        $this->deleteSource($path);
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
            model(ResourceModel::class)->delete($resource->id);
        } catch (Exception $e) {
            return false;
        }
        // remove file
        $path = ROOTPATH . $resource->getPath(false);
        if (file_exists($path) && !unlink($path)) {
            return false;
        };
        $this->deleteSource($path);
        return true;
    }

    public function getUnused(array $source, array &$result, string $path = 'writable/uploads') : void
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
     * Helper function, removes the parent of given path if it exists
     * and is an empty directory.
     *
     * @param string $path direct child of directory we want to remove
     */
    private function deleteSource($path) : void
    {
        helper('filesystem');
        // get parent
        $path = explode(DIRECTORY_SEPARATOR, $path);
        array_pop($path);
        $path = implode(DIRECTORY_SEPARATOR, $path);

        // remove folder
        if (is_dir($path) && count(directory_map($path, 0, true)) === 0) {
            rmdir($path);
        }
    }

    /**
     * Helper function to revert move from tmp_path to a material.
     * @param Resource $resource this will be moved if it exists
     */
    private function revertSave(Resource $resource) : void
    {
        $file = new File(ROOTPATH . $resource->getPath(false));
        if ($file->getRealPath()) {
            $file->move(ROOTPATH . $resource->tmp_path);
        }
    }
}

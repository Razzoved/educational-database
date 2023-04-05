<?php declare(strict_types = 1);

namespace App\Libraries;

use App\Entities\Resource;
use CodeIgniter\Files\File;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\HTTP\Response;
use Exception;

/**
 * Library for working with files without the need to take care of database
 * records. Mainly aimed at usage inside of controllers.
 *
 * @author Jan Martinek
 */
class Resources
{
    private $response;

    public function __construct($response)
    {
        if ($response === null) {
            throw new \Exception('missing caller');
        }
        $this->response = $response;
    }

    /**
     * Stores the file while updating its data so it can be used
     * elsewhere later.
     *
     * @param File $file splfile we want to store
     */
    public function store(UploadedFile $file) : Resource
    {
        helper('form');

        $date = date('Ymd', time());
        $dirPath = TEMP_PATH . $date;

        if (!is_dir($dirPath) && !mkdir($dirPath, 0777, true)) {
            return null;
        }

        if ($file->isValid() && !$file->hasMoved()) {
            $name = $file->getClientName();
            $resource = new Resource([
                'tmp_path'  => 'writable/uploads/' . $file->store(),
                'path'      => $name,
                'type'      => 'file'
            ]);
            if ($this->moveFile($resource, $dirPath)) {
                $resource->tmp_path = TEMP_PREFIX . $date . '/' . $resource->tmp_path;
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

        // cannot reassign
        if ($resource->isAssigned()) {
            return false;
        }

        // move old thumbnail to unused
        switch ($resource->type) {
            case 'thumbnail':
                $old = model(ResourceModel::class)->getThumbnail($materialId);
                if (count($old) > 0) $this->unassign($old[0]);
            case 'file':
                // create directory if it does not exist
                if (!is_dir(SAVE_PATH . $materialId) && !mkdir(SAVE_PATH . $materialId)) {
                    return false;
                }
                if (!$this->moveFile($resource, SAVE_PATH . $materialId)) {
                    return false;
                }
                $resource->path = $resource->tmp_path;
                unset($resource->tmp_path);
                break;
            default:
                break;
        }

        $resource->parentId = $materialId;
        return $this->saveToDatabase($resource, true);
    }

    /**
     * Move the given resource away from its parent. This means deletion
     * of record from DB and handling the removal of the file.
     * Acts differently for temporary files than assigned ones.
     *
     * @param Resource $resource
     */
    public function unassign(Resource $resource) : bool
    {
        helper('file');

        if ($resource->isTemporary()) {
            return $this->delete($resource);
        }

        // remove from db
        try {
            if ($resource->id > 0) model(ResourceModel::class)->delete($resource->id);
        } catch (Exception $e) {
            return false;
        }

        if ($resource->isAsset() || $resource->isLink()) {
            return true;
        }

        // create directory if it does not exist
        if (!is_dir(UNUSED_PATH) && !mkdir(UNUSED_PATH)) {
            return false;
        }

        if (!$this->moveFile($resource, UNUSED_PATH)) {
            return !file_exists(ROOTPATH . $resource->getRootPath());
        }

        return true;
    }

    /**
     * Deletes a single file from the server, given by the resource.
     * If resource contains parentId, also removes the DB record.
     * If you want the to delete from DB, you have to load resourceId.
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

        if ($resource->isAsset() || $resource->isLink()) {
            return true;
        }

        // remove file
        $path = ROOTPATH . $resource->getRootPath();
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

    public static function pathToURL(?string $rootPath) : string
    {
        $path = ASSET_PREFIX . 'missing.png';
        if ($rootPath && file_exists(ROOTPATH . $rootPath)) {
            $path = $rootPath;
        }
        return base_url($path);
    }

    public static function pathToFileURL(?string $rootPath) : string
    {
        $prefix = ASSET_PREFIX;
        $path = 'missing.png';

        if ($rootPath) {
            $splitPath = explode('.', $rootPath);
            $fileType = end($splitPath);
            switch ($fileType) {
                # images
                case 'png':
                case 'jpg':
                case 'jpeg':
                case 'bmp':
                case 'tiff':
                    $prefix = '';
                    $path = $rootPath;
                    break;
                # other file types
                case 'avi':
                    $path = 'file_avi.png';
                    break;
                case 'cdr':
                    $path = 'file_cdr.png';
                    break;
                case 'csv':
                    $path = 'file_csv.png';
                    break;
                case 'doc':
                case 'docx':
                    $path = 'file_doc.png';
                    break;
                case 'mp4':
                case 'mp3':
                    $path = 'file_mp3.png';
                    break;
                case 'pdf':
                    $path = 'file_pdf.png';
                    break;
                case 'ppt':
                case 'pptx':
                    $path = 'file_ppt.png';
                    break;
                case 'rar':
                    $path = 'file_rar.png';
                    break;
                case 'txt':
                    $path = 'file_txt.png';
                    break;
                case 'xls':
                    $path = 'file_xls.png';
                    break;
                case 'zip':
                    $path = 'file_zip.png';
                    break;
                default:
                    break;
            }
        }

        return base_url($prefix . $path);
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
     * Returns all unused files in array of Resource class objects.
     */
    private function doUnusedRecursive(array &$target, array $source, string $path) : void
    {
        foreach ($source as $key => $value) {
            $key = rtrim(rtrim((string) $key, '\\'), '/');
            $newPath = rtrim(rtrim($path, '\\'), '/');

            if (is_array($value)) {
                $newPath .= '/' . $key;
                if ($key < date('Ymd', time()) || str_starts_with($key, 'unused')) {
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
     * Helper function, tries to save resource into db. Can be set
     * to move file to its original location by doRevert parameter,
     * in case of failure.
     *
     * @param Resource $resource    resource to save
     * @param bool $doRevert        set to revert physical location
     */
    private function saveToDatabase(Resource $resource, bool $doRevert = false) : bool
    {
        helper('file');

        try {
            $model = model(ResourceModel::class);
            if ($model->getByPath($resource->parentId, $resource->path) === null) {
                $model->save($resource);
            }
        } catch (Exception $e) {
            $file = new File(ROOTPATH . $resource->getRootPath());
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
     * Loads the final file name to the tmp_path. Its up to the caller
     * to provide the prefix from root.
     *
     * WARN: unclean function, changes resource tmp_path and path.
     *
     * @param Resource $resource to be moved
     * @param string $dirPath directory where to move
     */
    private function moveFile(Resource &$resource, string $dirPath) : bool
    {
        helper('file');

        if ($resource->tmp_path === null) {
            $resource->tmp_path = $resource->getRootPath();
            $resource->path = basename($resource->path);
        }

        $path = ROOTPATH . $resource->tmp_path;

        $file = new File($path);
        if (!$file->getRealPath()) {
            return false;
        }

        if ($resource->isAsset()) {
            return copy($path, $dirPath . DIRECTORY_SEPARATOR . $resource->path);
        } else {
            $resource->tmp_path = $file->move($dirPath, $resource->path)->getBasename();
        }

        $this->deleteSource($path);
        return true;
    }
}

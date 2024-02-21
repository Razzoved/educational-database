<?php declare(strict_types = 1);

namespace App\Libraries;

use App\Entities\Resource as EntitiesResource;
use App\Models\ResourceModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Files\Exceptions\FileException;
use CodeIgniter\Files\File;
use CodeIgniter\HTTP\Files\UploadedFile;
use Exception;

/**
 * Library for working with files without the need to take care of database
 * records. Mainly aimed at usage inside of controllers.
 *
 * @author Jan Martinek
 */
class Resource
{
    private const EXT_IMAGE = [
        'apng',
        'png',
        'gif',
        'ico',
        'cur',
        'jpg',
        'jpeg',
        'jfif',
        'pjpeg',
        'pjp',
        'bmp',
        'tiff',
        'svg',
    ];

    private const EXT_SUPPORTED = [
        'png' => [
            'avi'  => 'avi',
            'cdr'  => 'cdr',
            'csv'  => 'csv',
            'doc'  => 'doc',
            'docx' => 'doc',
            'mp4'  => 'mp3',
            'mp3'  => 'mp3',
            'pdf'  => 'pdf',
            'ppt'  => 'ppt',
            'pptx' => 'ppt',
            'rar'  => 'rar',
            'txt'  => 'txt',
            'xls'  => 'xls',
            'zip'  => 'zip'
        ],
    ];

    /**
     * Creates a new resource from given path, optional tmpPath and type.
     *
     * @param ?string $path    Path to use for base location.
     * @param ?string $tmpPath Path to use for current location.
     * @param ?string $type    Type of resource to create.
     *
     * @return ?EntitiesResource If at least one of either path or tmpPath is provided.
     */
    public static function toResource(?string $path, ?string $tmpPath, ?string $type) : ?EntitiesResource
    {
        $resource = new EntitiesResource([
            'type'     => $type,
            'path'     => $path,
            'tmp_path' => $type === 'file' ? $tmpPath : $path
        ]);

        if (!$resource->isAsset() && !$resource->isLink()) {
            $resource->path = basename($path);
        }

        return ($resource->tmp_path || $resource->path) ? $resource : null;
    }

    /**
     * Stores the file while updating its data so it can be used
     * elsewhere later.
     *
     * @param UploadedFile $file file we want to store
     * @throws FileException     if storing fails
     * @return EntitiesResource  resource with all necessary information loaded
     */
    public static function store(UploadedFile $file) : EntitiesResource
    {
        helper('security');

        $date = date('Ymd', time());
        $prefix = TEMP_PREFIX . $date;

        if (!is_dir(ROOTPATH . $prefix) && !mkdir(ROOTPATH . $prefix, 0777, true)) {
            throw FileException::forExpectedDirectory('store: failed creation of directory');
        }

        if (!$file->isValid() || $file->hasMoved()) {
            throw FileException::forExpectedFile('store: invalid or moved file');
        }

        $resource = new EntitiesResource();
        $resource->tmp_path = 'writable/uploads/' . $file->store();
        $resource->path = urlencode(sanitize_filename($file->getClientName()));
        $resource->type = 'file';

        self::moveFile($resource, $prefix);

        return $resource;
    }

    /**
     * Assign a given resource to some material. If another material with
     * the same name already exists in targeted material, adds a number
     * behind assigned one.
     *
     * @param int $materialId               assign to this material
     * @param EntitiesResource $resource    what to operate on
     */
    public static function assign(int $materialId, EntitiesResource $resource) : void
    {
        helper('file');

        if ($resource->isAssigned()) {
            throw new Exception("assign: {$resource->path} already assigned to {$materialId}!");
        }

        switch ($resource->type) {
            case 'thumbnail':
                foreach (model(ResourceModel::class)->getThumbnail($materialId) as $old) {
                    self::unassign($old);
                }
            case 'file':
                if ($resource->isAsset()) {
                    break;
                }
                if (!is_dir(SAVE_PATH . $materialId) && !mkdir(SAVE_PATH . $materialId)) {
                    throw FileException::forExpectedDirectory('assign: ' . $materialId);
                }
                self::moveFile($resource, SAVE_PREFIX . $materialId);
            default:
                break;
        }

        $resource->parentId = $materialId;
        self::dbSafeSave($resource);
    }

    /**
     * Move the given resource away from its parent. This means deletion
     * of record from DB and handling the removal of the file.
     * Acts differently for temporary files than assigned ones.
     *
     * @param EntitiesResource $resource the resource to be moved
     *
     * @throws DatabaseException if deletion fails in db
     * @throws FileException     if unassigning fails
     */
    public static function unassign(EntitiesResource $resource) : void
    {
        helper('file');

        if ($resource->isTemporary()) {
            self::delete($resource);
            return;
        }

        if (self::dbDelete($resource)) {
            return;
        }

        // create directory if it does not exist
        if (!is_dir(TEMP_PATH . UNUSED) && !mkdir(TEMP_PATH . UNUSED)) {
            throw FileException::forExpectedDirectory('unassign: ' . UNUSED);
        }

        self::moveFile($resource, TEMP_PREFIX . UNUSED);
    }

    /**
     * This function deletes a resource and its associated file if it exists.
     *
     * @param EntitiesResource $resource A EntitiesResource object that represents a file to be deleted.
     *
     * @throws DatabasedException if resource is assigned and cannot be deleted
     * @throws FileException      if resource cannot be deleted
     */
    public static function delete(EntitiesResource $resource) : void
    {
        if (self::dbDelete($resource)) {
            return;
        }

        $path = ROOTPATH . $resource->getRootPath();

        if (file_exists($path) && !unlink($path)) {
            throw new FileException("File could not be deleted: {$path}");
        };

        self::deleteSource($path);
    }

    public static function pathToURL(?string $rootPath) : string
    {
        if ($rootPath && file_exists(ROOTPATH . $rootPath)) {
            $path = $rootPath;
        } else {
            $path = EntitiesResource::getDefaultImage()->path;
        }
        return base_url($path);
    }

    public static function pathToFileURL(?string $rootPath) : string
    {
        if ($rootPath) {
            $splitPath = explode('.', $rootPath);
            $fileType = end($splitPath);

            if (in_array($fileType, self::EXT_IMAGE)) {
                return base_url($rootPath);
            }

            foreach (self::EXT_SUPPORTED as $suffix => $supported) {
                if (isset($supported[$fileType])) {
                    return base_url(ASSET_PREFIX . "file_{$supported[$fileType]}.{$suffix}");
                }
            }
        }
        return base_url(EntitiesResource::getDefaultImage()->path);
    }

    /**
     * Returns all unused files in the form of a single-level array of EntitiesResource entity objects.
     *
     * @return array Collection of EntitiesResource entity objects
     */
    public static function getUnused() : array
    {
        helper('filesystem');

        $result = array();
        self::getUnusedRecursive(
            $result,
            directory_map(TEMP_PATH, 0, true),
            TEMP_PREFIX
        );

        return $result;
    }

    private static function getUnusedRecursive(array &$target, array $source, string $path) : void
    {
        foreach ($source as $key => $value) {
            $newPath = rtrim($path, WINDOWS_SEPARATOR . UNIX_SEPARATOR);
            $key = rtrim((string) $key, WINDOWS_SEPARATOR . UNIX_SEPARATOR);

            if (is_array($value)) {
                $newPath .= "/{$key}";
                if (strlen($key) === 8 && is_numeric($key) && $key >= date('Ymd', time())) {
                    continue;
                }
                self::getUnusedRecursive($target, $value, $newPath);
            } else if (substr($value, 0, 5) !== 'index') {
                $r = new EntitiesResource();
                $r->path = "{$newPath}/{$value}";
                $r->type = 'file';
                $target[] = $r;
            }
        }
    }

    /**
     * Helper function, deletes resource from database. Indicates
     * whether the deletion needs to be performed on the file itself.
     *
     * @param EntitiesResource $resource the resource to delete from database
     * @throws DatabaseException if deletion fails
     * @return bool              true if the deletion should end
     */
    private static function dbDelete(EntitiesResource $resource) : bool
    {
        if ($resource->isAssigned()) {
            model(ResourceModel::class)->delete($resource->id);
        }

        if ($resource->isAsset() || $resource->isLink()) {
            return true;
        }

        return false;
    }

    /**
     * Helper function, tries to save resource into db. Tries to move
     * the file into the original location on failure.
     *
     * @param EntitiesResource $resource    resource to save
     *
     * @throws Exception in case of db failure
     */
    private static function dbSafeSave(EntitiesResource $resource) : void
    {
        helper('file');

        try {
            model(ResourceModel::class)->save($resource->toRawArray());
        } catch (Exception $e) {
            if ($resource->type === 'file' || $resource->type === 'thumbnail') {
                $file = new File(ROOTPATH . $resource->getRootPath());
                if ($file->getRealPath()) {
                    $file->move(ROOTPATH . $resource->tmp_path);
                }
            }
            throw $e;
        }
    }

    /**
     * Helper function, removes the parent of given path if it exists
     * and is an empty directory.
     *
     * @param string $path direct child of directory we want to remove
     *
     * @throws FileException whenever the directory could be deleted, but failed to do so
     */
    private static function deleteSource(string $path) : void
    {
        helper('filesystem');

        $path = dirname($path);

        if (!is_dir($path)) {
            throw FileException::forExpectedDirectory('deleteSource');
        }

        $dirMap = directory_map($path, 0, true);
        if (count($dirMap) === 1 && $dirMap === [0 => 'index.html'] && !unlink($path . DIRECTORY_SEPARATOR . 'index.html')) {
            throw new FileException('Could not remove source directory - index file is still present');
        }

        $dirName = basename($path) . UNIX_SEPARATOR;
        $dirMap = directory_map($path, 0, true);
        if (empty($dirMap) && !($dirName === TEMP || $dirName === UNUSED) && !rmdir($path)) {
            throw new FileException('Could not remove source directory - rmdir failed');
        }
    }

    /**
     * This function tries to move (or copy an asset) from a path
     * given by:
     * - resource path    - if tmpPath is missing uses it as tmpPath and path
     * - resource tmpPath - if present uses path as final name
     * - if both missing throws error
     *
     * Target of the operation is the ROOTPATH + $prefix.
     *
     * WARN: unclean function, changes resource tmp_path and path.
     *
     * @param EntitiesResource $resource to be moved
     * @param string $prefix             where to move
     *
     * @throws FileException when file cannot be moved
     */
    private static function moveFile(EntitiesResource &$resource, string $prefix) : void
    {
        helper('file');

        if (!$resource->path && !$resource->tmp_path) {
            throw FileException::forExpectedFile('moveFile');
        }
        if (!$resource->path) {
            $resource->path = basename($resource->tmp_path);
        }
        if (!$resource->tmp_path) {
            $resource->tmp_path = $resource->getRootPath();
            $resource->path = basename($resource->path);
        }

        $dirPath = ROOTPATH . $prefix . UNIX_SEPARATOR;
        $path = ROOTPATH . $resource->tmp_path;

        $consumer = $resource->isAsset() ? 'copy' : 'move';

        $result = self::$consumer(
            $dirPath,
            $resource->path,
            $consumer === 'move' ? new File($path, true) : $path
        );

        if (!file_exists($dirPath . $result)) {
            self::deleteSource($dirPath . $result);
            throw FileException::forUnableToMove($resource->tmp_path, $prefix . $resource->path);
        }

        if (!file_exists($path)) {
            self::deleteSource($path);
        }

        $resource->path = $result;
        $resource->tmp_path = $prefix . UNIX_SEPARATOR . $result;
    }

    private static function move(string $dirPath, string $destName, File $source) : string
    {
        return $source->move($dirPath, $destName)->getBasename();
    }

    private static function copy(string $dirPath, string $destName, string $source) : string
    {
        try {
            if (!copy($source, $dirPath . $destName)) {
                throw new Exception('Copying failed');
            }
        } catch (Exception $e) {
            throw FileException::forUnableToMove($source, $dirPath . $destName, 'Could not copy file');
        }
        return $destName;
    }
}

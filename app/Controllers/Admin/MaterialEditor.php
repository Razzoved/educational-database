<?php declare(strict_types = 1);

namespace App\Controllers\Admin;

use App\Entities\Material as EntitiesMaterial;
use App\Entities\Property as EntitiesProperty;
use App\Entities\Resource as EntitiesResource;
use App\Exceptions\BadPostException;
use App\Libraries\Resources;
use App\Models\MaterialModel;
use App\Models\PropertyModel;
use App\Models\ResourceModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Files\Exceptions\FileException;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Psr\Log\LoggerInterface;

/**
 * Controls the logic for handling requests on manipulation of materials.
 * This includes: creation, modification, deletion of materials.
 *
 * @author Jan Martinek
 */
class MaterialEditor extends ResponseController
{
    private MaterialModel $materials;
    private ResourceModel $resources;
    private PropertyModel $properties;
    private Resources $resourceLibrary;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->materials = model(MaterialModel::class);
        $this->resources = model(ResourceModel::class, true, $this->materials->db);
        $this->properties = model(PropertyModel::class, true, $this->materials->db);
        $this->resourceLibrary = new Resources($this->response);
    }

    public function index(EntitiesMaterial $material = new EntitiesMaterial(), array $errors = []) : string
    {
        return view(Config::VIEW . 'material/form', [
            'meta_title'           => "Administration - material editor",
            'material'             => $material,
            'errors'               => $errors,
            'available_properties' => $this->properties->where('property_tag', 0)->getArray(),
            'available_relations'  => $this->materials->allowCallbacks(false)->where('material_id !=', $material->id)->getArray(),
        ]);
    }

    public function get(int $id) : string
    {
        $material = $this->materials->get($id);
        if (!$material) {
            throw PageNotFoundException::forPageNotFound();
        }
        return $this->index($material);
    }

    /**
     * Tries to convert post data to a single material and save it onto the
     * server. This operation includes both database and file organization.
     *
     * Requires following _POST format:
     *      compulsory material attributes
     *      optional properties - properties (array of ids)
     *      optional resources - thumbnail, files, links (key => val array)
     *      unused_files (key => val array)
     */
    public function save()
    {
        $material = new EntitiesMaterial($this->request->getPost());

        try {
            $this->transformData($material);
            $this->materials->saveMaterial($material);
            $this->deleteRemovedFiles($material);
            $this->moveTemporaryFiles($material);
        } catch (BadPostException $e) {
            return $this->index(
                new EntitiesMaterial($this->request->getPost()),
                array($e->getMessage())
            );
        } catch (FileException $e) {
            return $this->index($material, array($e->getMessage()));
        } catch (Exception $e) {
            if (!empty($this->materials->errors())) {
                $errors = $this->materials->errors();
            } else if (!empty($this->resources->errors())) {
                $errors = $this->resources->errors();
            } else if (!empty($this->properties->errors())) {
                $errors = $this->properties->errors();
            } else {
                $errors = array('Unknown error occurred, try again later!');
            }
            return $this->index($material, $errors);
        }

        return redirect()->to(url_to('Admin\Material::index'));
    }

    /** ----------------------------------------------------------------------
     *                           AJAX HANDLERS
     *  ------------------------------------------------------------------- */

    public function delete(int $id) : ResponseInterface
    {
        return $this->doDelete(
            $id,
            $this->materials->get,
            function ($e) {
                $this->deleteResources($e);
                $this->materials->delete($e->id);
            },
            'material'
        );
    }

    /** ----------------------------------------------------------------------
     *                           HELPER METHODS
     *  ------------------------------------------------------------------- */

     /**
      * Fills the material's fields that should contain objects with objects.
      *
      * @return EntitiesMaterial resulting object, subdata may be only partial
      * @throws BadPostException if the post data is invalid
      */
    private function transformData(EntitiesMaterial $material) : EntitiesMaterial
    {
        $material->related = $this->toRelations($material->id);
        $material->properties = $this->toProperties();
        $material->resources = [];
        $this->toResources($material, 'file');
        $this->toResources($material, 'link');
        $this->toResources($material, 'thumbnail');
        return $material;
    }

    private function toResources(EntitiesMaterial &$material, string $type) : void
    {
        $items = $this->request->getPost($type);
        if ($items && !is_array($items)) {
            throw new BadPostException('Resources: ' . $type . ' must be an array or null.');
        }
        foreach ($items as $tmpPath => $path) {
            switch ($type) {
                case 'file':
                case 'thumbnail':
                    $path = basename($path);
                    break;
                case 'link':
                    $tmpPath = $path;
                    break;
                default:
                    throw new BadPostException('Resource type: ' . $type . ' not supported');
            }
            if (!$tmpPath && !$path) continue;
            if (isset($material->resources[$tmpPath])) {
                throw new BadPostException('Resource: ' . esc($tmpPath) . ' already added');
            }
            $material->resources[$tmpPath] = new EntitiesResource([
                'parentId' => $material->id,
                'type'     => $type,
                'path'     => $path,
                'tmp_path' => $tmpPath,
            ]);
        }
    }

    private function toProperties() : array
    {
        $result = [];
        foreach ($this->request->getPost('properties') ?? [] as $id) {
            if (isset($result[$id])) {
                throw new BadPostException('Property: ' . $id . ' already added');
            }
            $result[$id] = new EntitiesProperty(['id' => $id]);
        }
        return $result;
    }

    private function toRelations(int $identifier) : array
    {
        $result = [];
        foreach ($this->request->getPost('relations') ?? [] as $id) {
            if ($identifier === $id) {
                throw new BadPostException('Cannot have relation with itself.');
            }
            if (isset($result[$id])) {
                throw new BadPostException('Relation: ' . $id . ' already added');
            }
            $result[$id] = new EntitiesMaterial(['id' => $id]);
        }
        return $result;
    }

    /**
     * Moves all temporary files from temporary directory to public one
     * belonging to the given material, and renames them back to their
     * original name if possible. Method copies assets.
     *
     * @param EntitiesMaterial $material material whose resources to move
     * @return self to enable method chaining
    */
    private function moveTemporaryFiles(EntitiesMaterial $material) : MaterialEditor
    {
        foreach ($material->resources as $r) {
            $found = $this->resources->getByPath(
                $material->id,
                basename($r->path)
            );
            if ($found === null) {
                $this->resourceLibrary->assign($material->id, $r);
            }
        }
        return $this;
    }

    /**
     * Deletes the newly unused resources from filesystem. This method
     * ignores all resources that are located in /assets.
     *
     * @param EntitiesMaterial $material material whose resources to remove
     * @return self to enable method chaining
     */
    private function deleteRemovedFiles(EntitiesMaterial $material) : MaterialEditor
    {
        foreach ($this->request->getPost('unused_files') ?? [] as $path) {
            $path = substr($path, 0, 4) === 'http'
                || substr($path, 0, strlen(TEMP_PREFIX)) === TEMP_PREFIX
                || substr($path, 0, strlen(ASSET_PREFIX)) === ASSET_PREFIX
                ? $path
                : basename($path);
            $resource = $this->resources->getByPath($material->id, $path);
            $this->resourceLibrary->unassign(
                $resource ?? new EntitiesResource(['path' => $path, 'tmp_path' => $path])
            );
        }
        return $this;
    }

    /**
     * Deletes the newly unused resources from filesystem. This method
     * ignores all resources that are located in /assets.
     *
     * @param EntitiesMaterial $material material whose resources to remove
     * @return self to enable method chaining
     */
    private function deleteResources(EntitiesMaterial $material) : MaterialEditor
    {
        foreach ($material->resources as $resource) {
            $this->resourceLibrary->delete($resource);
        }
        return $this;
    }
}

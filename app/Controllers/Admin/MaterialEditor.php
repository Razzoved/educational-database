<?php declare(strict_types = 1);

namespace App\Controllers\Admin;

use App\Entities\Material as EntitiesMaterial;
use App\Entities\Property as EntitiesProperty;
use App\Entities\Resource as EntitiesResource;
use App\Exceptions\BadPostException;
use App\Libraries\Property as PropertyLib;
use App\Libraries\Resource as ResourceLib;
use App\Models\MaterialModel;
use App\Models\PropertyModel;
use App\Models\ResourceModel;
use CodeIgniter\Config\Services;
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
    private const RULES = [
        'id'           => 'permit_empty|is_natural',
        'thumbnail'    => 'required|string|valid_image',
        'title'        => 'required|string|min_length[1]',
        'status'       => 'required|valid_status',
        'content'      => 'permit_empty|string',
        'properties'   => 'permit_empty|valid_properties',
        'file'         => 'permit_empty|valid_files',
        'link'         => 'permit_empty|valid_links',
        'relation'     => 'permit_empty|valid_related',
        // disallow following properties
        'related'      => 'permit_empty|null_only',
        'views'        => 'permit_empty|null_only',
        'rating'       => 'permit_empty|null_only',
        'rating_count' => 'permit_empty|null_only',
        'updated_at'   => 'permit_empty|null_only',
        'resources'    => 'permit_empty|null_only',
    ];

    private MaterialModel $materials;
    private ResourceModel $resources;
    private PropertyModel $properties;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->materials = model(MaterialModel::class);
        $this->resources = model(ResourceModel::class, true, $this->materials->db);
        $this->properties = model(PropertyModel::class, true, $this->materials->db);
    }

    public function index(EntitiesMaterial $material = new EntitiesMaterial(), array $errors = []) : string
    {
        return $this->view('material/form', [
            'meta_title' => "Administration - Material",
            'material'   => $material,
            'errors'     => $errors,
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
        } catch (\Exception $e) {
            Services::logger()->error('MaterialEditor: saving unrecoverable', $this->request->getPost() ?? []);
            throw $e;
        }

        if (!$this->validate(self::RULES)) {
            return $this->index($material, $this->validator->getErrors());
        }

        try {
            $this->materials->saveMaterial($material);
            $this->deleteRemovedFiles($material);
            $this->moveTemporaryFiles($material);
        } catch (Exception $e) {
            return $this->index($material, $this->getException($e));
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
            function ($id) {
                return $this->materials->get($id);
            },
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
        $items = $this->request->getPost($type) ?? [];
        $items = is_array($items) ? $items : [$items];

        $resources = $material->resources;
        foreach ($items as $tmpPath => $path) {
            $resource = ResourceLib::toResource($path, (string) $tmpPath, $type);
            if ($resource) {
                $resources[] = $resource;
            }
        }
        $material->resources = $resources;
    }

    private function toProperties() : array
    {
        $valid = $this->request->getPost('properties') ?? [];
        $valid = is_array($valid) ? $valid : array($valid);

        return PropertyLib::getFiltered($valid);
    }

    private function toRelations(int $identifier) : array
    {
        $result = [];
        foreach ($this->request->getPost('relation') ?? [] as $id => $unused) {
            if (is_numeric($id) && $identifier !== $id) {
                $result[] = $this->materials->allowCallbacks(false)->get($id);
            }
        }
        return $result;
    }

    private function getException(Exception $e)
    {
        if (!empty($this->materials->errors())) {
            return $this->materials->errors();
        } else if (!empty($this->resources->errors())) {
            return $this->resources->errors();
        } else if (!empty($this->properties->errors())) {
            return $this->properties->errors();
        }
        return array($e->getMessage());
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
                $r->path
            );

            if ($found === null) {
                ResourceLib::assign($material->id, $r);
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
            $resource = ResourceLib::toResource($path, null, 'file');
            $existing = $this->resources->getByPath($material->id, $resource->path);
            ResourceLib::unassign($existing ?? $resource);
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
            ResourceLib::delete($resource);
        }
        return $this;
    }
}

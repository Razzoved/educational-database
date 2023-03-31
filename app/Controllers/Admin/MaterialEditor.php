<?php declare(strict_types = 1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Entities\Material as EntitiesMaterial;
use App\Entities\Property as EntitiesProperty;
use App\Entities\Resource as EntitiesResource;
use App\Libraries\Resources;
use App\Models\MaterialMaterialModel;
use App\Models\MaterialModel;
use App\Models\PropertyModel;
use App\Models\ResourceModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Psr\Log\LoggerInterface;

/**
 * Controls the logic for handling requests on manipulation of materials.
 * This includes: creation, modification, deletion of materials.
 *
 * @author Jan Martinek
 */
class MaterialEditor extends BaseController
{
    private const META_TITLE = "Administration - material editor";

    private MaterialModel $materials;
    private PropertyModel $properties;
    private Resources $resourceLibrary;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->materials = model(MaterialModel::class);
        $this->properties = model(PropertyModel::class);
        $this->resourceLibrary = new Resources($this->response);
    }

    public function index() : string
    {
        $data = [
            'meta_title'           => self::META_TITLE,
            'errors'               => $data['errors'] ?? [],
            'available_properties' => $this->getAllPropertiesAsStrings(),
            'available_relations'  => $this->getAllMaterialsAsStrings(),
        ];
        return view(Config::VIEW . 'material/form', $data);
    }

    public function get(int $id) : string
    {
        $material = $this->materials->get($id);
        if (!$material)
            throw PageNotFoundException::forPageNotFound();
        return $this->setupPost($material)->index();
    }

    /**1
     * Tries to convert post data to a single material and save it onto the
     * server. This operation includes both database and file organization.
     *
     * Requires following _POST format:
     *      compulsory material attributes
     *      optional properties - properties (tag => [vals] array)
     *      optional resources - thumbnail, files, links (key => val array)
     *      unused_files (
     */
    public function save()
    {
        $material = new EntitiesMaterial($this->request->getPost());

        $material->resources = $this->loadResources(
            $this->request->getPost('thumbnail'),
            $this->request->getPost('files'),
            $this->request->getPost('links')
        );

        $material->properties = $this->loadProperties(
            $this->request->getPost('properties')
        );

        $material->related = $this->loadRelated(
            $material->id ?? $material->title,
            $this->request->getPost('relations')
        );

        try {
            $material->id = $this->materials->createOrUpdate($material);
            $this->deleteRemovedFiles($material)
                 ->moveTemporaryFiles($material);
        } catch (Exception $e) {
            $data['errors'] = $this->materials->errors();
            return $this->setupPost($material)->index();
        }

        return redirect('admin/materials');
    }

    /**
     * Method that responds to AJAX requests that contain an ID
     * attribute. It removes material of given ID.
     *
     * If successful, material is echoed back to the client.
     */
    public function delete() : void
    {
        if (!$this->request->isAJAX()) {
            $this->response->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED, "Request is not an AJAX request");
            return;
        }

        $id = $this->request->getPost('id');
        if ($id <= 0) {
            $this->resourceLibrary->echoError('Invalid material id');
            return;
        }

        $material = $this->materials->getById((int) $id);
        if ($material === null) {
            $this->resourceLibrary->echoError('Material does not exist');
            return;
        }

        try {
            $this->deleteResources($material);
            $this->materials->delete($material->id);
        } catch (Exception $e) {
            $this->resourceLibrary->echoError('Could not delete material: ' . $e->getMessage());
            return;
        }

        echo json_encode($material->id);
    }

    /**
     * Loads a single material into the post for easier handling in views.
     *
     * @param   EntitiesMaterial $material material to load
     * @return  self to enable method chaining
     */
    private function setupPost(EntitiesMaterial $material) : MaterialEditor
    {
        $properties = array();
        foreach ($material->properties as $p) {
            $properties[$p->tag][] = $p->value;
        }

        $links = array();
        foreach ($material->getLinks() as $r) {
            $links[] = $r->getURL();
        }

        $files = array();
        foreach ($material->getFiles() as $r) {
            $files[$r->getRootPath()] = $r->getName();
        }

        $relations = array();
        foreach (model(MaterialMaterialModel::class)->getRelated($material->id) as $r) {
            $relations[$r->id] = $r->title;
        }

        $_POST = [
            'id'         => $material->id,
            'author'     => $material->author,
            'status'     => $material->status,
            'title'      => $material->title,
            'content'    => $material->content,
            'thumbnail'  => $material->getThumbnail()->getRootPath(),
            'properties' => $properties,
            'links'      => $links,
            'files'      => $files,
            'relations'  => $relations,
        ];

        return $this;
    }

    private function loadResources(?string $thumbnail, ?array $files, ?array $links) : array
    {
        $resources = array();

        $this->toResources($resources, is_null($thumbnail) ? [] : [$thumbnail], 'thumbnail');
        $this->toResources($resources, $files ?? [], 'file');
        $this->toResources($resources, $links ?? [], 'link');

        return $resources;
    }

    private function toResources(array &$target, array $items, string $type) : void
    {
        foreach ($items ?? [] as $tmpPath => $path) {
            if (!$tmpPath && !$path) continue;
            $target[] = new EntitiesResource([
                'type'     => $type,
                'path'     => $type === 'link' ? $path : basename($path),
                'tmp_path' => is_numeric($tmpPath) ? $path : $tmpPath,
            ]);
        }
    }

    private function loadProperties(?array $properties) : array
    {
        $result = [];
        foreach ($properties ?? [] as $tag => $values) {
            foreach ($values as $value) {
                $p = $this->properties->getByBoth($tag, $value);
                if ($p) $result[] = $p;
            }
        }
        return $result;
    }

    private function loadRelated(mixed $identifier, ?array $relations) : array
    {
        $result = [];
        foreach ($relations ?? [] as $id => $value) {
            if ((is_int($identifier) && $id !== $identifier) ||
                (!is_int($identifier) && $value !== $identifier))
                $result[] = $id;
        }
        return $result;
    }

    /**
     * Gets all properties as an array of ['tag' => 'values'] records. This
     * method should be used before sending material to form.
     *
     * @return array array of stringified properties (['tag' => 'values'])
     */
    private function getAllPropertiesAsStrings() : array
    {
        $properties = [];
        foreach ($this->properties->getData()->get()->getResult(EntitiesProperty::class) as $property) {
            $properties[$property->tag][] = $property->value;
        }
        return $properties;
    }

    /**
     * Gets all materials as an array of ['id' => 'title'] records. This
     * method should be used before sending material to form.
     *
     * @return array array of stringified materials (['id' => 'title'])
     */
    private function getAllMaterialsAsStrings() : array
    {
        $materials = [];
        foreach ($this->materials->getArray(['callbacks' => false]) as $material) {
            $materials[$material->id] = $material->title;
        }
        return $materials;
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
            $found = model(ResourceModel::class)->getByPath(
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
            $resource = model(ResourceModel::class)->getByPath($material->id, $path);
            if ($resource === null) {
                $resource = new EntitiesResource(['path' => $path, 'tmp_path' => $path]);
            }
            $this->resourceLibrary->unassign($resource);
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

<?php declare(strict_types = 1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Entities\Material as EntitiesMaterial;
use App\Entities\Property as EntitiesProperty;
use App\Entities\Resource as EntitiesResource;
use App\Models\MaterialMaterialModel;
use App\Models\MaterialModel;
use App\Models\PropertyModel;
use CodeIgniter\Config\Services;
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

    private array $rules = [
        'title' => [
            'rules' => "required|string",
            'errors' => [],
        ],
        'author' => [
            'rules' => "required|string",
            'errors' => [],
        ],
        'status' => [
            'rules' => "required|validStatus",
            'errors' => [],
        ],
        'content' => [
            'rules' => "string",
            'errors' => [],
        ],
    ];

    private MaterialModel $materials;
    private PropertyModel $properties;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->materials = model(MaterialModel::class);
        $this->properties = model(PropertyModel::class);
    }

    public function index() : string
    {
        $data = [
            'meta_title'           => self::META_TITLE,
            'validation'           => Services::validation(),
            'available_properties' => $this->getAllPropertiesAsStrings(),
            'available_relations'  => $this->getAllMaterialsAsStrings(),
        ];
        return view(Config::VIEW . 'material/form', $data);
    }

    public function get(int $id) : string
    {
        $material = $this->materials->getById($id, true);
        if (!$material) {
            throw PageNotFoundException::forPageNotFound();
        }
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
        if (!$this->validate($rules)) {
            return $this->getEditorErrorView();
        }

        $material = new EntitiesMaterial($this->request->getPost());

        $this->resourceFromArray($material->resources, $this->request->getPost('thumbnail'), 'thumbnail');
        $this->resourceFromArray($material->resources, $this->request->getPost('files'), 'file');
        $this->resourceFromArray($material->resources, $this->request->getPost('links'), 'link');

        $material->properties = $this->loadProperties(
            $this->request->getPost('properties')
        );

        try {
            $this->materials->handleUpdate($material, $this->request->getPost('relations') ?? []);
            $this->moveTemporaryFiles($material)
                 ->deleteRemovedFiles();
        } catch (Exception $e) {
            $this->validator->setError('saving:', $e->getMessage());
            return $this->setupPost($material)->getEditorErrorView();
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
        $id = $this->request->getPost('id') ?? -1;
        if ($id < 0) {
            $this->response->setStatusCode(Response::HTTP_NOT_ACCEPTABLE);
            echo view('errors/error_modal', [
                'title' => 'Validation error',
                'message' => "Id is required"
            ]);
            return;
        }
        $material = $this->materials->getById((int) $id, true);
        if ($material === null) {
            $this->response->setStatusCode(Response::HTTP_NOT_ACCEPTABLE);
            echo view('errors/error_modal', [
                'title' => 'Database error',
                'message' => "Material not found"
            ]);
            return;
        }

        try {
            $this->materials->delete($material->id);
            $this->deleteRemovedFiles(array(
                $material->getThumbnail()->getPath(false)
            ));
            $this->deleteRemovedFiles(array_map(
                function($r) { return $r->getPath(false); },
                $material->getFiles()
            ));
            $dirPath = ROOTPATH . '/public/uploads/' . $material->id;
            if (is_dir($dirPath)) rmdir($dirPath);
        } catch (Exception $e) {
            $this->response->setStatusCode(Response::HTTP_CONFLICT);
            echo view('errors/error_modal', [
                'title' => 'Material saving erro',
                'message' => $e->getMessage()
            ]);
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
        $links = array();
        foreach ($material->getLinks() as $r) {
            $links[] = $r->getPath();
        }

        $files = array();
        foreach ($material->getFiles() as $r) {
            $files[$r->getPath(false)] = $r->getName();
        }

        $_POST = [
            'id' => $material->id,
            'author' => $material->author,
            'status' => $material->status,
            'title' => $material->title,
            'content' => $material->content,
            'properties' => $material->getPropertiesAsStrings(),
            'thumbnail' => $material->getThumbnail()->getPath(false),
            'links' => $links,
            'files' => $files,
            'relations' => model(MaterialMaterialModel::class)->getRelated($material->id, false, true),
        ];

        return $this;
    }

    /**
     * Non-clean helper function that adds resources to target.
     * Ignores all paths that lead to resources marked as ignore.
     * {@see App\Entities\Resource::ignore(?string)}
     *
     * @param array $target saves resources here
     * @param array $items  takes data from here
     * @param array $type   type to be added
     */
    private function resourcesFromArray(array &$target, ?array $items, string $type) : void
    {
        foreach ($items ?? [] as $tmpPath => $path) {
            if (EntitiesResource::ignore($path)) continue;
            $target[] = new EntitiesResource([
                'type'     => $type,
                'path'     => $type === 'link' ? $path : $this->lastSegment($path),
                'tmp_path' => is_numeric($tmpPath) ? $path : $tmpPath,
            ]);
        }
    }

    private function lastSegment(string $path) : string
    {
        while (str_contains($path, DIRECTORY_SEPARATOR)) {
            $path = explode(DIRECTORY_SEPARATOR, $path, 2)[1];
        }
        return $path;
    }

    /**
     * Gets the properties from ['tag' => 'values'] records.
     * Filters out all properties that do not exist in DB.
     *
     * @param ?array $properties properties to find ids for
     * @return array of App\Entities\Property objects
     */
    private function propertiesFromArray(?array $properties) : array
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
        foreach ($this->materials->getData()->get()->getResult(EntitiesMaterial::class) as $material) {
            $materials[$material->id] = $material->title;
        }
        return $materials;
    }

    /**
     * Moves all temporary files from writable directory to public one
     * belonging to the given material, and renames them back to their
     * original name if possible.
     *
     * @param EntitiesMaterial $material material whose resources to move
     * @return self to enable method chaining
    */
    private function moveTemporaryFiles(EntitiesMaterial $material) : MaterialEditor
    {
        foreach ($material->resources as $r) {
            $r->parentId = $material->id;
            if (isset($r->tmp_path) && 'writable' === substr($r->tmp_path, 0, 8)) {
                $file = new \CodeIgniter\Files\File(ROOTPATH . '/' . $r->tmp_path, true);
                $dirPath = ROOTPATH . "/public/uploads/$material->id";
                if (!is_dir($dirPath)) mkdir($dirPath, 0777, true);
                $file->move($dirPath, $r->path, true);
            }
        }
        return $this;
    }

    /**
     * Deletes the newly unused resources from filesystem. This method
     * ignores all resources that are located in /assets.
     *
     * @param ?array $unused resources to be deleted
     * @return self to enable method chaining
     */
    private function deleteRemovedFiles() : MaterialEditor
    {
        $unused = $this->request->getPost('unused_files') ?? [];
        foreach ($unused as $path) {
            if ('public/assets' === substr($path, 0, 13)) continue;
            $file = new \CodeIgniter\Files\File(ROOTPATH . '/' . ('uploads' === substr($path, 0, 7) ? 'public/' . $path : $path));
            if ($file->getRealPath()) unlink($file->getRealPath());
        }
        return $this;
    }

    /**
     * Template for form display in case of error. Its possible to use
     * $this->validator->setError() manually before calling this method.
     */
    private function getEditorErrorView() : string
    {
        return view(
            Config::VIEW . 'material/form',
            [
                'meta_title'           => MaterialEditor::META_TITLE,
                'validation'           => $this->validator,
                'available_properties' => $this->getAllPropertiesAsStrings(),
                'available_relations'  => $this->getAllMaterialsAsStrings(),
            ]
        );
    }
}

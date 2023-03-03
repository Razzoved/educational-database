<?php declare(strict_types = 1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Entities\Material as EntitiesMaterial;
use App\Entities\Property as EntitiesProperty;
use App\Entities\Resource as EntitiesResource;
use App\Models\MaterialMaterialModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Models\MaterialModel;
use App\Models\PropertyModel;
use CodeIgniter\Config\Services;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\Response;
use Exception;

class MaterialEditor extends BaseController
{
    const META_TITLE = "Administration - material editor";
    const INPUT_DIV = "<div class='row g-0 edit-mb'>";

    private array $messages = [];
    private MaterialModel $materials;
    private PropertyModel $properties;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->materials = model(MaterialModel::class);
        $this->properties = model(PropertyModel::class);
    }

    public function index() : string
    {
        $data = [
            'meta_title'           => MaterialEditor::META_TITLE,
            'validation'           => Services::validation(),
            'available_properties' => $this->getAllPropertiesAsStrings(),
            'available_relations'  => $this->getAllMaterialsAsStrings(),
        ];

        return view(Config::VIEW . 'material/form', $data);
    }

    public function loadMaterial(int $id) : string
    {
        $material = $this->materials->getById($id, true) ?? throw PageNotFoundException::forPageNotFound();
        $this->materialToPost($material);
        return $this->index();
    }

    public function save() : mixed
    {
        $rules = [
            'title'     => "required|string",
            'author'    => "required|string",
            'status'    => "required",
            'content'   => "string",
        ];

        if (!$this->validate($rules, $this->messages)) {
            return $this->getEditorErrorView();
        }

        $material = new EntitiesMaterial($this->request->getPost());
        $material->resources = $this->loadResources(
            $this->request->getPost('thumbnail'),
            $this->request->getPost('files'),
            $this->request->getPost('links'),
        );
        $material->properties = $this->loadProperties($this->request->getPost('properties'));

        try {
            $this->materials->handleUpdate($material, $this->request->getPost('relations') ?? []);
            $this->deleteRemovedFiles($this->request->getPost('unused-files'));
            $this->moveTemporaryFiles($material);
        } catch (Exception $e) {
            $this->validator->setError('Exception while saving:', $e->getMessage());
            $this->materialToPost($material);
            return $this->getEditorErrorView();
        }

        return redirect()->to('admin/materials');
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
            $this->response->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED, "Request is not an AJAX request")->send();
            return;
        }
        $id = $this->request->getPost('id') ?? -1;
        if ($id < 0) {
            $this->response->setStatusCode(Response::HTTP_NOT_ACCEPTABLE, "Id is required")->send();
            return;
        }
        $material = $this->materials->getById((int) $id, true);
        if ($material === null) {
            $this->response->setStatusCode(Response::HTTP_NOT_ACCEPTABLE, "Material not found")->send();
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
            $this->response->setStatusCode(Response::HTTP_CONFLICT, $e->getMessage())->send();
            return;
        }

        echo json_encode($material->id);
    }

    /**
     * Loads a single material into the post for display to the user.
     * @param EntitiesMaterial $material material to load
     */
    private function materialToPost(EntitiesMaterial $material) : void
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
    }

    /**
     * Looks through the properties from post and filters them:
     * - ignores files with paths to MISSING
     * - saves temporary paths to tmp_path
     * - saves normalized (without first 2 segments) original paths to path
     *
     * @param ?string $thumbnail - path to thumbnail
     * @param ?array $files
     * @param ?array $links
     *
     * @return array Filtered resources
     */
    private function loadResources(?string $thumbnail, ?array $files, ?array $links) : array
    {
        $resources = array();

        $this->loadFromArray($resources, is_null($thumbnail) ? [] : [$thumbnail], 'thumbnail');
        $this->loadFromArray($resources, $files ?? [], 'file');
        $this->loadFromArray($resources, $links ?? [], 'link');

        return $resources;
    }

    /**
     * Non-clean helper function that adds resources to target.
     *
     * @param array $target saves resources here
     * @param array $items takes resources from it
     * @param array $type type of resources to be added
     */
    private function loadFromArray(array &$target, array $items, string $type) : void
    {
        foreach ($items as $tmpPath => $path) {
            if (EntitiesResource::isMissing($path)) continue;
            $target[] = new EntitiesResource([
                'type'     => $type,
                'path'     => $type === 'link' ? $path : $this->normalize($path),
                'tmp_path' => is_numeric($tmpPath)
                    ? $path
                    : $tmpPath,
            ]);
        }
    }

    /**
     * Returns the last segment of the path.
     *
     * @param string $path path to be normalized
     * @return string last segment of path
     */
    private function normalize(string $path) : string
    {
        while (str_contains($path, '/')) {
            $path = explode('/', $path, 2)[1];
        }
        while (str_contains($path, '\\')) {
            $path = explode('\\', $path, 2)[1];
        }
        return $path;
    }

    /**
     * Gets the properties all values loaded for a given array of
     * ['tag' => 'values'] records.
     *
     * @param ?array $properties properties to find ids for
     * @return array properties with ids
     */
    private function loadProperties(?array $properties) : array
    {
        $result = array();
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
    */
    private function moveTemporaryFiles(EntitiesMaterial $material) : void
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
    }

    /**
     * Deletes the newly unused resources from filesystem. This method
     * ignores all resources that are located in /assets.
     *
     * @param ?array $unused resources to be deleted
     */
    private function deleteRemovedFiles(?array $unused) : void
    {
        foreach ($unused ?? [] as $path) {
            if ('/assets' === substr($path, 0, 7)) continue;
            $file = new \CodeIgniter\Files\File(ROOTPATH . '/' . ('uploads' === substr($path, 0, 7) ? 'public/' . $path : $path));
            if ($file->getRealPath()) unlink($file->getRealPath());
        }
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

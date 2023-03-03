<?php declare(strict_types = 1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Models\PropertyModel;
use App\Models\MaterialPropertyModel;
use CodeIgniter\Config\Services;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\Response;
use Exception;

class Property extends BaseController
{
    private const META_TITLE = 'Administration - tags';

    private PropertyModel $properties;
    private MaterialPropertyModel $materialProperties;

    protected array $messages = [
        'tag' => ['uniqueProperty' => 'Cannot create a duplicate tag!'],
    ];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->properties = model(PropertyModel::class);
        $this->materialProperties = model(MaterialPropertyModel::class);
    }

    public function index() : string
    {
        $filters = [];

        $tags = $this->properties->getTags();
        if ($tags !== []) {
            $filters['Tags'] = $tags;
        }

        $values = $this->properties->getDuplicateValues();
        if ($values !== []) {
            $filters['Values'] = $values;
        }

        $data = [
            'meta_title' => Property::META_TITLE,
            'title'      => 'Tags',
            'properties'  => $this->getProperties(current_url(), Config::PAGE_SIZE),
            'filters' => $filters,
            'pager'      => $this->properties->pager,
        ];

        return view(Config::VIEW . 'property/table', $data);
    }

    public function edit(int $id) : string
    {
        $property = $this->properties->find($id);

        if ($property === null) throw PageNotFoundException::forPageNotFound();

        $_POST['id'] = $property->id;
        $_POST['tag'] = $property->tag;
        $_POST['value'] = $property->value;

        $data = [
            'meta_title' => Property::META_TITLE . ' editor',
            'validation' => Services::validation(),
        ];

        return view(Config::VIEW . 'property/form', $data);
    }

    public function update() : string|RedirectResponse
    {
        $rules = [
            'id'       => "required|integer",
            'tag'      => "required|string",
            'value'    => "required|string",
        ];

        if (!$this->validate($rules)) {
            return $this->getEditorErrorView($this->validator);
        }

        $property = new \App\Entities\Property([
            'id'    => $this->request->getPost('id'),
            'tag'   => $this->request->getPost('tag'),
            'value' => $this->request->getPost('value')
        ]);

        try {
            $this->properties->update($property->id, $property);
        } catch (Exception $e) {
            $this->validator->setError('database', $e->getMessage());
            return $this->getEditorErrorView($this->validator);
        }

        return redirect()->to(base_url('admin/tags'));
    }

    public function save() : void
    {
        $value = $this->request->getPost('value');
        $rules = [
            'tag'      => "required|string|uniqueProperty[{$value}]",
            'value'    => "required|string",
        ];

        if (!$this->validate($rules)) {
            $this->response->setStatusCode(Response::HTTP_NOT_ACCEPTABLE, json_encode($this->validator->getErrors()));
            return;
        }

        $property = new \App\Entities\Property([
            'tag'   => $this->request->getPost('tag'),
            'value' => $this->request->getPost('value')
        ]);

        try {
            $id = $this->properties->insert($property, true);
            echo json_encode($this->materialProperties->getPropertyWithUsage($id));
        } catch (Exception $e) {
            $this->response->setStatusCode(Response::HTTP_NOT_ACCEPTABLE, $e->getMessage());
        }
    }

    public function delete() : void
    {
        if (!$this->request->isAJAX()) {
            $this->response->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED);
            return;
        }

        if (!$this->validate(['id' => "required|integer"])) {
            $this->response->setStatusCode(Response::HTTP_NOT_ACCEPTABLE, 'invalid id');
            return;
        }

        // find property
        $id = $this->request->getPostGet('id');
        $property = $this->materialProperties->getPropertyWithUsage((int) $id);

        // build and send response
        if (!is_null($property) && $property->usage == 0) {
            $this->properties->delete($id);
            $this->response->setStatusCode(Response::HTTP_OK);
        } else {
            $this->response->setStatusCode(Response::HTTP_PRECONDITION_FAILED, 'Already in use by ' . $property->usage . ' materials!');
        }
        $this->response->setJSON($property->id)->send();
    }

    private function getProperties(string $url, int $perPage = 10) : array
    {
        $uri = new \CodeIgniter\HTTP\URI($url);

        $properties = $this->loadProperties()
                           ->paginate($perPage, segment: $uri->getTotalSegments());

        foreach ($properties as $p) {
            $p->usage = $this->materialProperties->getPropertyUsage($p->id);
        }
        return $properties;
    }

    private function loadProperties(): PropertyModel
    {
        $sort = $this->request->getPost('sort');
        $sortDir = $this->request->getPost('sortDir');
        $search = $this->request->getPost('search') ?? "";
        $filters = $this->request->getPost('filters') ?? [];

        return ($search !== "" || $filters !== [])
            ? $this->properties->getByFilters($sort, $sortDir, $search, $filters)
            : $this->properties->getData($sort, $sortDir);
    }

    private function getEditorErrorView(\CodeIgniter\Validation\Validation $validator) : string
    {
        return view(
            Config::VIEW . 'property/form',
            [
                'meta_title' => Property::META_TITLE . ' editor',
                'validation' => $validator,
            ]
        );
    }
}

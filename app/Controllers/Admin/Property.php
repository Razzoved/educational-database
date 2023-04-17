<?php declare(strict_types = 1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MaterialPropertyModel;
use App\Models\PropertyModel;
use CodeIgniter\Config\Services;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Exception;

class Property extends BaseController
{
    private const META_TITLE = 'Administration - tags';

    private PropertyModel $properties;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->properties = model(PropertyModel::class);
    }

    public function index() : string
    {
        $filters = [
            'Tags'   => array_map(function ($p) { return $p->tag; }, $this->properties->getUnique('tag')),
            'Values' => array_map(function ($p) { return $p->value; }, $this->properties->getDuplicates('value')),
        ];

        $data = [
            'meta_title' => Property::META_TITLE,
            'title'      => 'Tags',
            'properties' => $this->getProperties(Config::PAGE_SIZE),
            'options'    => $this->getOptions($filters['Tags']),
            'filters'    => $filters,
            'pager'      => $this->properties->pager,
            'activePage' => 'tags',
        ];

        return view(Config::VIEW . 'property/table', $data);
    }

    public function edit(int $id) : string
    {
        $property = $this->properties->find($id);
        if ($property === null)
            throw PageNotFoundException::forPageNotFound();

        $_POST['id'] = $property->id;
        $_POST['tag'] = $property->tag;
        $_POST['value'] = $property->value;

        $data = [
            'meta_title' => Property::META_TITLE . ' editor',
            'validation' => Services::validation(),
        ];

        return view(Config::VIEW . 'property/form', $data);
    }

    public function update()
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

        return redirect()->to(url_to('Admin\Property::index'));
    }

    public function save() : void
    {
        $value = $this->request->getPost('value');
        $rules = [
            'tag'      => "required|string|uniqueProperty[{$value}]|not_in_list[search,sort,sortDir]",
            'value'    => "required|string",
        ];

        if (!$this->validate($rules, ['tag' => ['uniqueProperty' => 'This tag-value pair is already taken!']])) {
            $this->response->setStatusCode(Response::HTTP_NOT_ACCEPTABLE);
            echo view('errors/error_modal', [
                'title' => 'Validation error',
                'message' => $this->validator->listErrors()
            ]);
            return;
        }

        $property = new \App\Entities\Property([
            'tag'   => $this->request->getPost('tag'),
            'value' => $this->request->getPost('value')
        ]);

        try {
            $id = $this->properties->insert($property, true);
            echo json_encode($this->properties->get($id, ['callbacks' => true]));
        } catch (Exception $e) {
            $this->response->setStatusCode(Response::HTTP_NOT_ACCEPTABLE);
            echo view('errors/error_modal', [
                'title' => 'Validation error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete() : void
    {
        if (!$this->request->isAJAX()) {
            $this->response->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED);
            return;
        }

        if (!$this->validate(['id' => "required|integer"])) {
            $this->response->setStatusCode(Response::HTTP_NOT_ACCEPTABLE);
            echo view('errors/error_modal', [
                'title' => 'Validation error',
                'message' => 'Given id is not valid!'
            ]);
            return;
        }

        $id = (int) $this->request->getPostGet('id');
        $property = $this->properties->get($id, ['callbacks' => true]);

        if (!is_null($property) && $property->usage == 0) {
            $this->properties->delete($id);
            echo json_encode($property->id);
        } else {
            $this->response->setStatusCode(Response::HTTP_PRECONDITION_FAILED);
            echo view('errors/error_modal', [
                'title' => 'Database error',
                'message' => 'Already in use by ' . $property->usage . ' materials!'
            ]);
        }
    }

    protected function getOptions(array $result = []) : array
    {
        foreach ($this->properties->getUnique('value') as $property) {
            $result[] = $property->value;
        }
        return $result;
    }

    protected function getProperties(int $perPage = 20) : array
    {
        return $this->properties->getPage(
            (int) $this->request->getGetPost('page') ?? 1 ,
            [
                'filters'   => \App\Libraries\Property::getFilters($this->request->getGetPost() ?? []),
                'search'    => $this->request->getGetPost('search'),
                'sort'      => $this->request->getGetPost('sort'),
                'sortDir'   => $this->request->getGetPost('sortDir'),
                'callbacks' => false,
                'usage'     => true,
            ],
            $perPage
        );
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

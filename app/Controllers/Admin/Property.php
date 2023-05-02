<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Entities\Property as EntitiesProperty;
use App\Models\PropertyModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Exception;

class Property extends ResponseController
{
    private PropertyModel $properties;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->properties = model(PropertyModel::class);
    }

    public function index() : string
    {
        $filters = new EntitiesProperty(['value' => 'Categories']);
        $filters->children = $this->properties
            ->where('property_tag', 0)
            ->getArray(['callbacks' => true]);
        $this->getCategories($filters);

        $data = [
            'meta_title' => 'Administration - tags',
            'title'      => 'Tags',
            'properties' => $this->getProperties(Config::PAGE_SIZE),
            'options'    => $this->getOptions(),
            'filters'    => array($filters),
            'pager'      => $this->properties->pager,
            'activePage' => 'tags',
        ];
        return view(Config::VIEW . 'property/table', $data);
    }

    /** ----------------------------------------------------------------------
     *                           AJAX HANDLERS
     *  ------------------------------------------------------------------- */

    public function save() : Response
    {
        $property = new EntitiesProperty($this->request->getPost());
        $rules = [
            'tag'      => "required|is_natural",
            'value'    => "required|string|not_in_list[page,search,sort,sortDir]|property_value_update[{tag},{id}]",
        ];

        if (!$this->validate($rules)) {
            return $this->toResponse(
                $property,
                $this->validator->getErrors(),
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            if (!$this->properties->save($property)) throw new Exception();
            if (!$property->id) $property->id = $this->properties->getInsertID();
            $property->usage = $this->properties->getUsage($property->id);
        } catch (Exception $e) {
            return $this->toResponse(
                $property,
                ['error' => 'Could not save property, try again later!'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return $this->response->setJSON($property);
    }

    public function get(int $id) : Response
    {
        $property = $this->properties->find($id);
        if (!$property) {
            return $this->response->setStatusCode(
                Response::HTTP_NOT_FOUND,
                'Property with id ' . $id . ' not found!'
            );
        }
        return $this->response->setJSON($property);
    }

    public function delete(int $id) : Response
    {
        return $this->doDelete(
            $id,
            function ($i) {
                return $this->properties->find($i);
            },
            function ($e) { $this->properties->delete($e->id); },
            'tag'
        );
    }

    /** ----------------------------------------------------------------------
     *                           HELPER METHODS
     *  ------------------------------------------------------------------- */

    protected function getOptions(array $result = []): array
    {
        foreach ($this->properties->getUnique('value') as $property) {
            $result[] = $property->value;
        }
        return $result;
    }

    protected function getProperties(int $perPage = 20): array
    {
        return $this->properties->getPage(
            (int) $this->request->getGetPost('page') ?? 1,
            [
                'filters'   => \App\Libraries\Property::getFilters($this->request),
                'search'    => $this->request->getGetPost('search'),
                'sort'      => $this->request->getGetPost('sort'),
                'sortDir'   => $this->request->getGetPost('sortDir'),
                'callbacks' => false,
                'category'  => true,
                'usage'     => true,
            ],
            $perPage
        );
    }

    protected function getCategories(EntitiesProperty &$source) : void
    {
        $children = $source->children;
        foreach ($children as $k => $child) {
            if (empty($child->children)) {
                unset($children[$k]);
            } else {
                $this->getCategories($child);
            }
        }
        $source->__set('children', $children);
    }
}

<?php declare(strict_types = 1);

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Exceptions\PageNotFoundException;
use Psr\Log\LoggerInterface;

use App\Models\MaterialModel;
use App\Models\ResourceModel;

class Material extends BaseController
{
    private MaterialModel $materials;
    private ResourceModel $resources;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->materials = model(MaterialModel::class);
        $this->resources = model(ResourceModel::class);
        // E.g.: $this->session = \Config\Services::session();
    }

    public function index(int $page) : string {
        $data = [
            'meta_title' => 'Materials',
            'title' => 'Materials',
            'filters' => $this->materials->getUsedProperties(),
            'page' => max($page, 0)
        ];

        $request = $this->request->getPost();
        if ($request) {
            // var_dump($request);
            $data['materials'] = $this->materials->findFiltered(
                (isset($_POST['search'])) ? $_POST['search'] : "",
                (isset($_POST['filters'])) ? $_POST['filters'] : [],
                10,
                $page
            );
        } else {
            $data['materials'] = $this->materials->findAll(10, 10 * $page);
        }

        return view('material_multiple', $data);
    }

    public function get(int $id) : string {
        $material = $this->materials->findWithProperties($id);

        if (!$material) throw PageNotFoundException::forPageNotFound();

        $material->resources = $this->resources->getResources($id);

        $data = [
            'meta_title' => $material->title,
            'title' => $material->title,
            'material' => $material,
        ];

        return view('material_single', $data);
    }
}

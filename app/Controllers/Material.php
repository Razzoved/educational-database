<?php declare(strict_types = 1);

namespace App\Controllers;

use App\Entities\Material as EntitiesMaterial;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Exceptions\PageNotFoundException;
use Psr\Log\LoggerInterface;

use App\Models\MaterialModel;
use App\Models\MaterialPropertyModel;
use App\Models\RatingsModel;
use App\Models\ResourceModel;

class Material extends BaseController
{
    protected MaterialModel $materials;
    protected MaterialPropertyModel $materialProperties;
    protected ResourceModel $resources;
    protected RatingsModel $ratings;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) : void
    {
        parent::initController($request, $response, $logger);

        $this->materials = model(MaterialModel::class);
        $this->materialProperties = model(MaterialPropertyModel::class);
        $this->resources = model(ResourceModel::class);
        $this->ratings = model(RatingsModel::class);

        // E.g.: $this->session = \Config\Services::session();
    }

    /**
     * Returns a view of a given page. If the page number is greater than
     * total number of pages, it returns the last page.
     */
    public function index() : string
    {
        $data = [
            'meta_title' => 'Materials',
            'title'      => 'Materials',
            'filters'    => $this->materialProperties->getUsedProperties(),
            'materials'  => $this->getMaterials(current_url()),
            'pager'      => $this->materials->pager,
        ];

        return view('material_multiple', $data);
    }

    /**
     * Returns a view of a single material. If the material is not found, it will return
     * the page not found error.
     *
     * @param int $page number of the page (0 <= $page < number of pages)
     */
    public function get(int $id) : string
    {
        $material = $this->getMaterial($id);
        $session = session();

        if ($id && !$session->has('m-' . $id)) {
            $session->set('m-' . $id, true);
            $material->views++;
            try {
                $this->materials->update($material->id, $material);
            } catch (\Exception $e) {
                // this is not a fatal error and may be ignored
            };
        }

        $data = [
            'meta_title' => $material->title,
            'title' => $material->title,
            'material' => $material,
        ];

        return view('material_single', $data);
    }

    protected function getMaterial(int $id) : EntitiesMaterial
    {
        $material = $this->materials->getById($id);
        if (!$material) throw PageNotFoundException::forPageNotFound();
        return $material;
    }

    protected function getMaterials(string $url, int $perPage = 10) : array
    {
        $uri = new \CodeIgniter\HTTP\URI($url);

        $materials = $this->loadMaterials()
                          ->paginate($perPage, segment: $uri->getTotalSegments());

        foreach ($materials as $m) {
            $m->resources = $this->resources->getThumbnail($m->id);
            $m->rating = $this->ratings->getRatingAvg($m->id);
            $m->rating_count = $this->ratings->getRatingCount($m->id);
        }
        return $materials;
    }

    protected function loadMaterials() : MaterialModel
    {
        $sort = $this->request->getPost('sort');
        $sortDir = $this->request->getPost('sortDir');
        $search = $this->request->getPost('search') ?? "";
        $filters = $this->request->getPost('filters') ?? [];

        return ($search !== "" && $filters !== [])
            ? $this->materials->getByFilters($sort, $sortDir, $search, $filters)
            : $this->materials->getData($sort, $sortDir);
    }
}

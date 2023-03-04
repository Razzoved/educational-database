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
use App\Models\ViewsModel;

class Material extends BaseController
{
    protected MaterialModel $materials;
    protected MaterialPropertyModel $materialProperties;
    protected ResourceModel $resources;
    protected RatingsModel $ratings;
    protected ViewsModel $views;

    protected bool $onlyPublic = true;

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
        $this->views = model(ViewsModel::class);

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
            'activePage' => '',
        ];

        return view('material_multiple', $data);
    }

    /**
     * Returns a view of a given page. If the page number is greater than
     * total number of pages, it returns the last page.
     */
    public function mostViewed() : string
    {
        $data = [
            'meta_title' => 'Materials - monthly views',
            'title'      => 'Most viewed materials in 30 days',
            'filters'    => [],
            'materials'  => $this->views->getTopMaterials(50),
            'pager'      => null,
            'activePage' => 'most-viewed',
        ];

        return view('material_views', $data);
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
            try {
                $this->views->increment($material);
            } catch (\Exception $e) {
                // not critical
            };
        }

        $data = [
            'meta_title' => $material->title,
            'title' => $material->title,
            'material' => $material,
        ];

        return view('material_single', $data);
    }

    /**
     * AJAX request handler for rating updates. Echoes back the new rating values
     * (two integers - averaged rating, rating count)
     *
     * @uses $_POST['id'] id of material to rate
     * @uses $_POST['value'] value of rating to set for the user
     */
    public function rate() : void
    {
        $id = $this->request->getPost('id');
        $value = $this->request->getPost('value');
        $material = null;

        if (!$id) {
            echo "";
            return;
        }

        if (session_id() === "") {
            session(); // create a new session
        }

        if ($this->ratings->setRating($id, session_id(), $value)) {
            $material = $this->materials->find($id);
        }

        if (!$material) {
            echo "";
            return;
        }

        $material->rating = $this->ratings->getRatingAvg($id);
        $material->rating_count = $this->ratings->getRatingCount($id);
        $this->materials->update($id, $material);

        echo json_encode(['avg' => $material->rating, 'cnt' => $material->rating_count]);
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
                          ->paginate($perPage, 'default', null, $uri->getTotalSegments());

        foreach ($materials as $m) {
            $m->resources = $this->resources->getThumbnail($m->id);
        }
        return $materials;
    }

    protected function loadMaterials() : \CodeIgniter\BaseModel
    {
        $sort = $this->request->getPost('sort');
        $sortDir = $this->request->getPost('sortDir') ?? "ASC";
        $search = $this->request->getPost('search') ?? "";
        $filters = $this->request->getPost('filters') ?? [];

        return ($search !== "" || $filters !== [])
            ? $this->materials->getByFilters($sort, $sortDir, $search, $filters)
            : $this->materials->getData($sort, $sortDir);
    }
}

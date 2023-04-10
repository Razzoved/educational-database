<?php declare(strict_types = 1);

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Exceptions\PageNotFoundException;

use App\Models\MaterialModel;
use App\Models\MaterialPropertyModel;
use App\Models\RatingsModel;
use App\Models\ResourceModel;
use App\Models\ViewsModel;
use Psr\Log\LoggerInterface;

class Material extends BaseController
{
    protected MaterialModel $materials;
    protected MaterialPropertyModel $materialProperties;
    protected RatingsModel $ratings;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) : void
    {
        parent::initController($request, $response, $logger);

        $this->materials = model(MaterialModel::class);
        $this->materialProperties = model(MaterialPropertyModel::class);
        $this->ratings = model(RatingsModel::class);
    }

    /**
     * Returns a view of a given page. If the page number is greater than
     * total number of pages, it returns the last page.
     */
    public function index() : string
    {
        $data = [
            'meta_title' => 'Materials',
            'title'      => 'All materials',
            'filters'    => $this->materialProperties->getUsed(),
            'options'    => $this->getOptions(),
            'materials'  => $this->getMaterials(),
            'pager'      => $this->materials->pager,
            'activePage' => '',
        ];

        return view('material_multiple', $data);
    }

    /**
     * Returns a view of a single material. If the material is not found,
     * it will return the page not found error.
     *
     * @param int $page number of the page (0 <= $page < number of pages)
     */
    public function get(int $id) : string
    {
        $material = $this->materials->get($id);
        if (!$material)
            throw PageNotFoundException::forPageNotFound();

        // increment views if not viewed yet and user is not logged in
        $session = session();
        if ($id && !$session->has('m-' . $id) && !$session->get('isLoggedIn')) {
            $session->set('m-' . $id, true);
            model(ViewsModel::class)->increment($material);
        }

        $data = [
            'meta_title'    => $material->title,
            'title'         => $material->title,
            'material'      => $material,
            'rating'        => $this->ratings->getRating($material->id, session('id') ?? ''),
        ];

        return view('material_single', $data);
    }

    /**
     * AJAX request handler for rating updates. Echoes back the new rating values.
     *
     * @uses $_POST['id'] id of material to rate
     * @uses $_POST['value'] value of rating to set for the user
     */
    public function rate() : void
    {
        $id = (int) $this->request->getPost('id');
        $value = (int) $this->request->getPost('value');
        $material = null;

        if (!$id) return;
        if (session('id') === null) {
            session()->set('id', session_id());
        }

        $newValue = $this->ratings->setRating($id, session('id'), $value);
        $material = ($newValue === null || $newValue === $value) ? $this->materials->find($id) : null;

        if ($material) {
            $material->rating = $this->ratings->getRatingAvg($id);
            $material->rating_count = $this->ratings->getRatingCount($id);
            $this->materials->update($id, $material);
        }

        echo json_encode([
            'average' => $material->rating ?? null,
            'count' => $material->rating_count ?? null,
            'user' => $newValue
        ]);
    }

    protected function getOptions() : array
    {
        return array_column(
            $this->materials->getArray(['sort' => 'title', 'callbacks' => false]),
            'material_title'
        );
    }

    protected function getMaterials(int $perPage = 10) : array
    {
        $uri = new \CodeIgniter\HTTP\URI(current_url());
        return $this->materials->getPage(
            $uri->getTotalSegments(),
            [
                'filters'   => \App\Libraries\Property::getFilters($this->request->getGetPost() ?? []),
                'search'    => $this->request->getGetPost('search'),
                'sort'      => $this->request->getGetPost('sort'),
                'sortDir'   => $this->request->getGetPost('sortDir'),
            ],
            $perPage
        );
    }
}

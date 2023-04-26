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
use CodeIgniter\HTTP\Response;
use Exception;
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

        return view('material/all', $data);
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

        return view('material/one', $data);
    }

    /**
     * AJAX request handler for rating updates. Echoes back the new rating values.
     *
     * @uses $_POST['id'] id of material to rate
     * @uses $_POST['value'] value of rating to set for the user
     */
    public function rate(int $id) : Response
    {
        $value = (int) $this->request->getPost('value');
        if (!$value) {
            return $this->response->setStatusCode(Response::HTTP_BAD_REQUEST, 'No value was given!');
        }

        $material = $this->materials->find($id);
        if (!$material) {
            return $this->response->setStatusCode(Response::HTTP_NOT_FOUND, 'Material not found!');
        }

        if (session('id') === null) {
            session()->set('id', session_id());
        }

        try {
            $newValue = $this->ratings->setRating($id, session('id'), $value);

            $material->rating = $this->ratings->getRatingAvg($id);
            $material->rating_count = $this->ratings->getRatingCount($id);

            if ($newValue === null || $newValue === $value) {
                $this->materials->update($id, $material);
            }
        } catch (Exception $e) {
            return $this->response->setStatusCode(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $e->getMessage(),
                // 'Unexpected error occured while rating, try again later!'
            );
        }

        return $this->response->setJSON([
            'average' => $material->rating,
            'count'   => $material->rating_count,
            'user'    => $newValue
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
        return $this->materials->getPage(
            (int) $this->request->getGetPost('page') ?? 1,
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

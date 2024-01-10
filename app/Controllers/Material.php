<?php

declare(strict_types=1);

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;

use App\Models\MaterialModel;
use App\Models\MaterialPropertyModel;
use App\Models\RatingsModel;
use App\Models\ViewsModel;
use CodeIgniter\HTTP\Response;
use Exception;

class Material extends DefaultController
{
    protected MaterialModel $materials;
    protected MaterialPropertyModel $materialProperties;
    protected RatingsModel $ratings;
    protected ViewsModel $views;

    protected function ready(): void
    {
        $this->materials = model(MaterialModel::class);
        $this->materialProperties = model(MaterialPropertyModel::class, true, $this->materials->db);
        $this->ratings = model(RatingsModel::class, true, $this->materials->db);
        $this->views = model(ViewsModel::class, true, $this->materials->db);
    }

    /**
     * Returns a view of a given page. If the page number is greater than
     * total number of pages, it returns the last page.
     */
    public function index(): string
    {
        return $this->_index('all', title: 'All materials');
    }

    public function getByRating(): string
    {
        $this->setSort('rating', 'DESC');
        return $this->_index('all', title: 'Top rated materials');
    }

    public function getByViews(): string
    {
        $this->setSort('views', 'DESC');
        return $this->_index('all', title: 'Most viewed materials');
    }

    /**
     * Returns a view of a single material. If the material is not found,
     * it will return the page not found error.
     *
     * @param int $page number of the page (0 <= $page < number of pages)
     */
    public function get(int $id): string
    {
        $material = $this->materials->get($id);
        if (!$material)
            throw PageNotFoundException::forPageNotFound();

        // increment views if not viewed yet and user is not logged in
        $session = session();
        if ($id && !$session->has('m-' . $id) && !$session->get('isLoggedIn')) {
            $session->set('m-' . $id, true);
            $this->views->increment($material);
        }

        return $this->view('material/one', [
            'meta_title' => $material->title,
            'title'      => $material->title,
            'material'   => $material,
            'rating'     => $this->ratings->getRating($material->id, session('id') ?? ''),
            'hasSidebar' => !empty($material->properties),
        ]);
    }

    /** ----------------------------------------------------------------------
     *                           AJAX HANDLERS
     *  ------------------------------------------------------------------- */

    /**
     * Request handler for rating updates. Echoes back the new rating values.
     *
     * @uses $_POST['id'] id of material to rate
     * @uses $_POST['value'] value of rating to set for the user
     */
    public function rate(int $id): Response
    {
        $rules = [
            'value' => 'required|is_natural|less_than_equal_to[5]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(
                Response::HTTP_BAD_REQUEST,
                $this->validator->getErrors()[0],
            );
        }

        $data = $this->validator->getValidated();
        $value = (int) $data['value'];
        $material = $this->materials->find($id);

        if (!$material) {
            return $this->response->setStatusCode(
                Response::HTTP_NOT_FOUND,
                'Material not found!'
            );
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
                'Unexpected error occured while rating, try again later!'
            );
        }

        return $this->response->setJSON([
            'average' => $material->rating,
            'count'   => $material->rating_count,
            'user'    => $newValue
        ]);
    }

    /** ----------------------------------------------------------------------
     *                               HELPERS
     *  ------------------------------------------------------------------- */

    protected function _index(
        string $view,
        string $meta = 'Materials',
        string $title = 'Materials',
        ?array $filters = null,
        ?array $materials = null,
        string $activePage = '',
    ): string {
        assert($view != '', 'Invalid view: cannot be empty!');

        return $this->view("material/{$view}", [
            'meta_title' => $meta,
            'title'      => $title,
            'filters'    => $filters ?? $this->materialProperties->getUsed(),
            'materials'  => $materials ?? $this->getMaterials(),
            'pager'      => $this->materials->pager,
            'activePage' => $activePage,
        ]);
    }

    protected function getMaterials(): array
    {
        return $this->materials->getPage(
            (int) $this->request->getGetPost('page') ?? 1,
            [
                'filters'   => \App\Libraries\Property::getFilters($this->request),
                'search'    => $this->request->getGetPost('search'),
                'sort'      => $this->request->getGetPost('sort'),
                'sortDir'   => $this->request->getGetPost('sortDir'),
            ],
            self::PAGE_SIZE
        );
    }
}

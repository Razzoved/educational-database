<?php declare(strict_types = 1);

namespace App\Controllers\Admin;

use App\Controllers\Material as ControllersMaterial;
use App\Entities\Material as EntitiesMaterial;
use App\Models\MaterialModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Material extends ControllersMaterial
{
    public function index() : string
    {
        $data = [
            'meta_title' => 'Administration - Materials',
            'title'      => 'Materials',
            'filters'    => $this->materialProperties->getUsedProperties(false),
            'materials'  => $this->getMaterials(current_url(), Config::PAGE_SIZE),
            'pager'      => $this->materials->pager,
            'activePage' => 'materials',
        ];

        return view(Config::VIEW . 'material/table', $data);
    }

    protected function getMaterial(int $id) : EntitiesMaterial
    {
        $material = $this->materials->getById($id, true);
        if (!$material) throw PageNotFoundException::forPageNotFound();
        return $material;
    }

    protected function loadMaterials() : MaterialModel
    {
        $sort = $this->request->getPost('sort');
        $sortDir = $this->request->getPost('sortDir') ?? 'ASC';
        $search = $this->request->getPost('search') ?? "";
        $filters = $this->request->getPost('filters') ?? [];

        return ($search !== "" || $filters !== [])
            ? $this->materials->getByFilters($sort, $sortDir, $search, $filters, false)
            : $this->materials->getData($sort, $sortDir, false);
    }
}

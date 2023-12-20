<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\Material as ControllersMaterial;
use CodeIgniter\HTTP\Response;

class Material extends ControllersMaterial
{
    public function index(): string
    {
        $this->setSort('updated_at');

        return $this->view('material/table', [
            'meta_title' => 'Administration - Materials',
            'title'      => 'Materials',
            'options'    => $this->getOptions(),
            'filters'    => $this->materialProperties->getUsed(),
            'materials'  => $this->getMaterials(ADMIN_PAGE_SIZE),
            'pager'      => $this->materials->pager,
        ]);
    }

    public function getAvailable(): Response
    {
        try {
            $materials = $this->materials->allowCallbacks(false)->getArray(['sort' => 'published_at']);
            return $this->response->setJSON($materials);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR, 'Could not get available materials');
        }
    }
}

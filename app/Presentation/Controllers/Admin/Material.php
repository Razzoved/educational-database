<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use CodeIgniter\HTTP\Response;

class Material extends \App\Controllers\Material
{
    public function index(): string
    {
        $this->setSort('updated_at');
        return $this->_index('table', meta: 'Administration - Materials');
    }

    public function getAvailable(): Response
    {
        try {
            $materials = $this->materials->allowCallbacks(false)->findAll(0, 0, ['sortBy' => 'published_at']);
            return $this->response->setJSON($materials);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR, 'Could not get available materials');
        }
    }
}

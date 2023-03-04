<?php declare(strict_types = 1);

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\Response;

class Resource extends BaseController
{
    public function writable(string ...$path)
    {
        if (!session()->get('isLoggedIn') || $path === []) {
            $this->response
                ->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED)
                ->send();
            return;
        };

        $fullpath = WRITEPATH;
        foreach ($path as $p) {
            $fullpath .= '/' . $p;
        }

        $resource = new \CodeIgniter\Files\File($fullpath, true);

        header('Content-Type: ' . $resource->getMimeType());
        echo file_get_contents($fullpath);
    }
}

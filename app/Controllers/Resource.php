<?php declare(strict_types = 1);

namespace App\Controllers;

use CodeIgniter\Files\File;
use CodeIgniter\HTTP\ResponseInterface;

class Resource extends BaseController
{
    public function writable(string ...$path)
    {
        if (!session('isLoggedIn') || $path === []) {
            $this->response
                ->setStatusCode(ResponseInterface::HTTP_METHOD_NOT_ALLOWED)
                ->send();
            return;
        };

        $fullpath = WRITEPATH;
        foreach ($path as $p) {
            $fullpath .= '/' . $p;
        }

        $resource = new File($fullpath, true);

        header('Content-Type: ' . $resource->getMimeType());
        echo file_get_contents($fullpath);
    }
}

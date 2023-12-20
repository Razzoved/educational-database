<?php

declare(strict_types=1);

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class CheckAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session('isLoggedIn')) {
            return redirect()->to(url_to('Admin\Dashboard::index'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // does nothing
    }
}

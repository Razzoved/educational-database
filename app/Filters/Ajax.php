<?php

declare(strict_types=1);

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Ajax implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // does nothing
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $response->setHeader(csrf_header(), csrf_hash());
    }
}

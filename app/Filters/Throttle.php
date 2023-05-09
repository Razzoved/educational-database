<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class Throttle implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $throttler = Services::throttler();

        // limits the submits from IP to 60 per minute
        if ($throttler->check(md5($request->getIPAddress()), 60, MINUTE) === false) {
            return Services::response()->setStatusCode(429)->setBody(view('throttler'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // does nothing
    }
}

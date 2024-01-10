<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class DefaultController
 *
 * This controller is an extension of BaseController, intended to better
 * isolate framework updates from the application codebase.
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class DefaultController extends BaseController
{
    protected const PAGE_SIZE = USER_PAGE_SIZE;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->ready();
    }

    /**
     * Alternative way to load variables than initController
     * with no need for extra arguments.
     */
    protected function ready()
    {
        // Intentionally does nothing
    }

    /**
     * Handles optional admin routes and automatically adds 'saveData'
     * to the view call.
     *
     * This is done to allow for layout extension in the called
     * view.
     *
     * @param string $path    path to view file
     * @param array  $data    optional data to be passed
     * @param array  $options optional view options
     */
    protected function view(string $path, array $data = [], array $options = []): string
    {
        if (strpos(get_class($this), '\\Admin\\') !== false) {
            $path = 'admin/' . $path;
        }
        return view($path, $data, array_merge($options, ['saveData' => true]));
    }

    protected function setSort(string $sort, ?string $sortDir = null)
    {
        $get = $this->request->getGet();
        $get['sort'] = $this->request->getGet('sort') ?? $sort;
        $get['sortDir'] = $this->request->getGet('sortDir') ?? $sortDir ?? 'DESC';
        $this->request->setGlobal('get', $get);
        $_GET = $get;
    }
}

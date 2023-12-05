<?php

namespace App\Controllers;

/**
 * Class ExtendedController
 *
 * This controller is an extension of BaseController, intended to better
 * isolate framework updates from the application codebase.
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class ExtendedController extends BaseController
{
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

    protected function setSort(string $sort)
    {
        $get = $this->request->getGet();
        $get['sort'] = $this->request->getGet('sort') ?? $sort;
        $this->request->setGlobal('get', $get);
        $_GET = $get;
    }
}

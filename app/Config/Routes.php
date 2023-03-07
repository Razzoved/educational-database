<?php

namespace Config;

use App\Controllers\Materials;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Material');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

/* Viewable by public */
$routes->group('/', function($routes) {
    // ALL materials
    $routes->addRedirect('', '1');
    $routes->addRedirect('-(:num)', '$1');
    $routes->add('(:num)', 'Material::index');

    // TOP rated materials
    $routes->addRedirect('top-rated', 'top-rated/1');
    $routes->addRedirect('top-rated/-(:num)', 'top-rated/$1');
    $routes->add('top-rated/(:num)', 'MaterialTopRated::index');

    // MOST viewed materials
    $routes->addRedirect('most-viewed', 'most-viewed/1');
    $routes->addRedirect('most-viewed/-(:num)', 'most-viewed/$1');
    $routes->add('most-viewed/(:num)', 'Material::mostViewed');

    // SINGLE material
    $routes->add('single/(:num)', 'Material::get/$1');
    $routes->post('single/rate', 'Material::rate');

    // AUTHENTICATION
    $routes->get('login', 'Login::index');
    $routes->post('login', 'Login::authenticate');

    // RESOURCE VIEWER
    $routes->get('writable/(:any)', 'Resource::writable/$1');
});

/* Viewable by authorised users */
$routes->group('admin', function($routes) {
    $routes->addRedirect('', 'admin/dashboard');
    $routes->add('dashboard', 'Admin\Dashboard::index');
    $routes->add('logout', 'Admin\User::logout');
    $routes->add('profile', 'Admin\User::profile');

    $routes->group('materials', function($routes) {
        $routes->addRedirect('', 'admin/materials/1');
        $routes->addRedirect('-(:num)', 'admin/materials/$1');
        $routes->add('(:num)', 'Admin\Material::index');
        $routes->get('edit', 'Admin\MaterialEditor::index');
        $routes->post('edit', 'Admin\MaterialEditor::save');
        $routes->add('edit/(:num)', 'Admin\MaterialEditor::loadMaterial/$1');
        $routes->add('delete', 'Admin\MaterialEditor::delete');
    });

    $routes->group('tags', function($routes) {
        $routes->addRedirect('', 'admin/tags/1');
        $routes->addRedirect('-(:num)', 'admin/tags/$1');
        $routes->add('(:num)', 'Admin\Property::index');
        $routes->get('edit/(:num)', 'Admin\Property::edit/$1');
        $routes->add('update', 'Admin\Property::update');
        $routes->add('save', 'Admin\Property::save');
        $routes->add('delete', 'Admin\Property::delete');
    });

    $routes->group('files', function($routes) {
        $routes->add('', 'Admin\Resource::index');
        $routes->post('upload', 'Admin\Resource::upload');
    });

    $routes->group('users', function($routes) {
        $routes->addRedirect('', 'admin/users/1');
        $routes->addRedirect('-(:num)', 'admin/users/$1');
        $routes->add('(:num)', 'Admin\User::index');
        $routes->get('edit', 'Admin\User::getUser');
        $routes->post('new', 'Admin\User::create');
        $routes->post('edit', 'Admin\User::update');
        $routes->post('delete', 'Admin\User::delete');
    });
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

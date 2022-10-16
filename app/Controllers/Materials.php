<?php

namespace App\Controllers;

class Materials extends BaseController
{
    public function index()
    {
        $data = [
            'meta_title' => 'All materials',
            'title' => 'All materials'
        ];
        return parent::wrapView('all_materials', $data);
    }

    public function post($id) {
        $data = [
            'meta_title' => "Material: $id",
            'title' => $id
        ];
        return parent::wrapView('single_material', $data);
    }
}

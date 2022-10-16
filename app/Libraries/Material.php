<?php namespace App\Libraries;

class Material
{
    public function postItem($params) {
        return view('components/post_material', $params);
    }
}
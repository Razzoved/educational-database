<?php declare(strict_types = 1);

namespace App\Libraries;

class Material
{
    public function postItem($params) {
        return view('components/post_material', $params);
    }
}
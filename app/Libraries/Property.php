<?php declare(strict_types = 1);

namespace App\Libraries;

use App\Models\PropertyGetter;

class Property
{
    public function postFilter(array $data) {
        return view(
            'components/post_filter',
            ['filter' => (new PropertyGetter(db_connect()))->getByType($data['filter'])]
        );
    }
}
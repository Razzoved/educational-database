<?php declare(strict_types = 1);

namespace App\Libraries;

class Post
{
    public function postItem(array $params) : string
    {
        return view('components/post_as_card', $params);
    }
}
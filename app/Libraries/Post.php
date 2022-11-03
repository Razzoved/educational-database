<?php declare(strict_types = 1);

namespace App\Libraries;

use App\Entities\Post as EntitiesPost;

class Post
{
    public function postItem(EntitiesPost $post) : string
    {
        return view('components/post_as_card', ['post' => $post]);
    }
}
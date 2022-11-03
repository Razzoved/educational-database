<?php declare(strict_types = 1);

namespace App\Libraries;

use App\Entities\Post;

class Material
{
    public function relatedMaterials(int $postId) : void
    {

    }

    public function getMaterialsList(Post $post) : string
    {
        if (!isset($post)) return 'NOT SET';
        return view('components/materials_as_links', ['materials' => $post->materials]);
    }
}
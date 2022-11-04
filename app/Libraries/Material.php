<?php declare(strict_types = 1);

namespace App\Libraries;

use App\Entities\Post;

class Material
{
    public function relatedMaterials(int $postId) : void
    {
        // TODO
    }

    public function getMaterialsList(Post $post) : string
    {
        if (!isset($post) || $post->materials == null || $post->materials == []) return 'NOT SET';
        return view('components/materials_as_links', ['materials' => $post->materials]);
    }
}

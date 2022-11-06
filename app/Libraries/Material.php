<?php declare(strict_types = 1);

namespace App\Libraries;

use App\Entities\Post;

class Material
{
    public function relatedMaterials(int $postId) : void
    {
        // TODO
    }

    public function getLinks(Post $post) : string
    {
        if (!isset($post)) return 'ERROR';

        $materials = array();

        foreach ($post->materials as $material) {
            if ($material->material_type == 'link') $materials[] = $material;
        }

        return view('components/materials_as_links', ['materials' => $materials, 'title' => 'Attached links']);
    }

    public function getFiles(Post $post) : string
    {
        if (!isset($post)) return 'ERROR';

        $materials = array();

        foreach ($post->materials as $material) {
            if ($material->material_type != 'link') $materials[] = $material;
        }

        return view('components/materials_as_links', ['materials' => $materials, 'title' => 'Downloadable files']);
    }
}

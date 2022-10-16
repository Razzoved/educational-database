<?php

namespace App\Controllers;

use App\Models\MaterialModel;

class Materials extends BaseController
{
    public function index()
    {

        $text = 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Magnam quibusdam quae atque laudantium sit nisi pariatur impedit aperiam odit illo, alias saepe facere! Illum magni deserunt nemo id quis aliquam!';

        $posts = [
            ['title' => 'Item 1', 'img' => 'assets/test_img.jpg', 'text' => $text, 'views' => '157', 'rating' => 4.5, 'uploadDate' => '20.03.2020', 'id' => 0],
            ['title' => 'Item 2', 'img' => 'assets/test_img.png', 'text' => $text, 'views' => '21', 'rating' => 2.3, 'uploadDate' => '01.08.2022', 'id' => 1],
            ['title' => 'Item 3', 'img' => 'assets/test_img.jpg', 'text' => $text, 'views' => '999989', 'rating' => 0.5, 'uploadDate' => '15.11.2018', 'id' => 2],
            ['title' => 'Item 4', 'img' => 'assets/test_img.jpg', 'text' => $text, 'views' => '5645', 'rating' => 4.5, 'uploadDate' => '15.09.2020', 'id' => 3],
            ['title' => 'Item 5', 'img' => 'assets/test_img.png', 'text' => $text, 'views' => '21321', 'rating' => 2.3, 'uploadDate' => '01.08.2022', 'id' => 4],
            ['title' => 'Item 6', 'img' => 'assets/test_img.png', 'text' => $text, 'views' => '267', 'rating' => 0.5, 'uploadDate' => '16.12.2018', 'id' => 5],
            ['title' => 'Item 7', 'img' => 'assets/test_img.jpg', 'text' => $text, 'views' => '100', 'rating' => 4.5, 'uploadDate' => '22.01.2022', 'id' => 6],
            ['title' => 'Item 8', 'img' => 'assets/test_img.jpg', 'text' => $text, 'views' => '0', 'rating' => 2.3, 'uploadDate' => '01.08.2022', 'id' => 7],
            ['title' => 'Item 9', 'img' => 'assets/test_img.png', 'text' => $text, 'views' => '15', 'rating' => 0.5, 'uploadDate' => '15.11.2018', 'id' => 8],
            ['title' => 'Item 10', 'img' => 'assets/test_img.jpg', 'text' => $text, 'views' => '654', 'rating' => 4.5, 'uploadDate' => '23.02.2019', 'id' => 9],
            ['title' => 'Item 11', 'img' => 'assets/test_img.jpg', 'text' => $text, 'views' => '32', 'rating' => 2.3, 'uploadDate' => '01.10.2022', 'id' => 10],
            ['title' => 'Item 12', 'img' => 'assets/test_img.jpg', 'text' => $text, 'views' => '957', 'rating' => 0.5, 'uploadDate' => '14.11.2018', 'id' => 11],
            ['title' => 'Item 13', 'img' => 'assets/test_img.jpg', 'text' => $text, 'views' => '65554777', 'rating' => 4.5, 'uploadDate' => '28.03.2020', 'id' => 12],
            ['title' => 'Item 14', 'img' => 'assets/test_img.png', 'text' => $text, 'views' => '54000', 'rating' => 2.3, 'uploadDate' => '03.09.2021', 'id' => 13],
            ['title' => 'Item 15', 'img' => 'assets/test_img.jpg', 'text' => $text, 'views' => '744', 'rating' => 0.5, 'uploadDate' => '17.11.2018', 'id' => 14],
        ];

        $data = [
            'meta_title' => 'Materials',
            'title' => 'Materials',
            'posts' => $posts
        ];

        return view('all_materials', $data);
    }

    public function post($id) {
        $model = new MaterialModel();
        $post = $model->find($id);
        if ($post) {
            $data = [
                'meta_title' => "Material: $id",
                'title' => $id,
                'post' => $post
            ];
        } else {
            $data = [
                'meta_title' => "Material not found",
            ];
        }

        return view('single_material', $data);
    }

    public function new() {
        $data = [
            'meta_title' => 'New material',
            'title' => 'Create new material'
        ];

        if ($this->request->getPost()) {
            echo '<pre>';
                print_r($_POST);
            echo '</pre>';
            $model = new MaterialModel();
            $model->save($_POST);
        }

        return view('new_material', $data);
    }

    public function delete($id) {
        $model = new MaterialModel();
        $post = $model->find($id);
        if ($post) {
            $model->delete($id);
            return redirect()->to('/');
        }
    }
}

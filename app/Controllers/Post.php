<?php declare(strict_types = 1);

namespace App\Controllers;

use App\Models\Tables\PostModel;

class Post extends BaseController
{
    public function index() : string {
        $text = 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Magnam quibusdam quae atque laudantium sit nisi pariatur impedit aperiam odit illo, alias saepe facere! Illum magni deserunt nemo id quis aliquam!';

        $data = [
            'meta_title' => 'Materials',
            'title' => 'Materials',
            'posts' => $posts
        ];

        return view('post_view_all', $data);
    }

    public function search(string $userInput) {

    }

    public function post($id) {
        $data = ['meta_title' => 'Post not found'];
        $post = (new PostModel())->find($id);
        if ($post) {
            $data = [
                'meta_title' => $post['post_title'],
                'title' => $post['post_title'],
                'post' => $post
            ];
        }
        return view('post_view_one', $data);
    }

    public function new() {
        $data = [
            'meta_title' => 'New post',
            'title' => 'Create new post'
        ];

        if ($this->request->getPost()) {
            (new PostModel())->save($_POST);
        }

        return view('post_new', $data);
    }

    public function edit($id) {
        $model = new PostModel();

        if ($this->request->getPost()) {
            $_POST['post_id'] = $id;
            $model->save($_POST);
        }

        $post = $model->find($id);
        if ($post) {
            $data = [
                'meta_title' => $post['post_title'],
                'title' => $post['post_title'],
                'post' => $post
            ];
            return view('post_edit', $data);
        }

        return redirect()->to("/$id");
    }

    public function delete($id) {
        $model = new PostModel();
        $post = $model->find($id);
        if ($post) {
            $model->delete($id);
            return redirect()->to('/');
        }
    }
}

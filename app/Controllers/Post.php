<?php declare(strict_types = 1);

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Models\PostModel;

class Post extends BaseController
{
    private PostModel $postModel;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->postModel = new PostModel();

        // E.g.: $this->session = \Config\Services::session();
    }

    public function index() : string {
        $data = [
            'meta_title' => 'Materials',
            'title' => 'Materials',
            'filters' => $this->postModel->getUsedProperties()
        ];

        if ($this->request->getPost()) {
            $data['posts'] = $this->postModel->filter($_POST);
        } else {
            $data['posts'] = $this->postModel->findAll();
        }

        return view('post_view_all', $data);
    }

    public function post($id) : string {
        $data = ['meta_title' => 'Post not found'];
        $post = $this->postModel->find($id);
        if ($post) {
            $data = [
                'meta_title' => $post->post_title,
                'title' => $post->post_title,
                'post' => $post,
            ];
        }
        return view('post_view_one', $data);
    }

    public function new() : string {
        $data = [
            'meta_title' => 'New post',
            'title' => 'Create new post'
        ];

        if ($this->request->getPost()) {
            $this->postModel->save($_POST);
        }

        return view('post_new', $data);
    }

    public function edit($id) : string {
        if ($this->request->getPost()) {
            $_POST['post_id'] = $id;
            $this->postModel->save($_POST);
        }

        $post = $this->postModel->find($id);
        if ($post) {
            $data = [
                'meta_title' => $post->post_title,
                'title' => $post->post_title,
                'post' => $post
            ];
            return view('post_edit', $data);
        }

        return redirect()->to("/$id");
    }

    public function delete($id) : string {
        $post = $this->postModel->find($id);
        if ($post) {
            $this->postModel->delete($id);
            return redirect()->to('/');
        }
        return $this->post($id);
    }
}

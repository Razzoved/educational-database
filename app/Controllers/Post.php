<?php declare(strict_types = 1);

namespace App\Controllers;

use App\Models\MaterialModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Exceptions\PageNotFoundException;
use Psr\Log\LoggerInterface;

use App\Models\PostModel;

class Post extends BaseController
{
    private PostModel $postModel;
    private MaterialModel $materialModel;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->postModel = new PostModel();
        $this->materialModel = new MaterialModel();

        // E.g.: $this->session = \Config\Services::session();
    }

    public function index(int $page) : string {
        $data = [
            'meta_title' => 'Materials',
            'title' => 'Materials',
            'filters' => $this->postModel->getUsedProperties(),
            'page' => max($page, 0)
        ];

        if ($this->request->getPost()) {
            $data['posts'] = $this->postModel->filter(
                (isset($_POST['search'])) ? $_POST['search'] : "",
                (isset($_POST['filters'])) ? $_POST['filters'] : [],
                10,
                $page
            );
        } else {
            $data['posts'] = $this->postModel->findAll(10, 10 * $page);
        }

        return view('post_view_page', $data);
    }

    public function post(int $id) : string {
        $post = $this->postModel->findWithProperties($id);

        if (!$post) throw PageNotFoundException::forPageNotFound();

        $post->materials = $this->materialModel->findMaterials($id);

        $data = [
            'meta_title' => $post->post_title,
            'title' => $post->post_title,
            'post' => $post,
        ];

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
        if (!$post) throw PageNotFoundException::forPageNotFound();

        $data = [
            'meta_title' => $post->post_title,
            'title' => $post->post_title,
            'post' => $post
        ];

        return view('post_edit', $data);
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

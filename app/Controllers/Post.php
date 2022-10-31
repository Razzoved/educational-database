<?php declare(strict_types = 1);

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Models\PostModel;
use App\Models\PostsPropertiesModel;
use App\Models\PropertyGetter;

class Post extends BaseController
{
    private PostModel $postModel;
    private PostsPropertiesModel $postsPropertiesModel;

    private PropertyGetter $propertyGetter;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->postModel = new PostModel();
        $this->postsPropertiesModel = new PostsPropertiesModel();

        $this->propertyGetter = new PropertyGetter(db_connect());

        // E.g.: $this->session = \Config\Services::session();
    }

    public function index() : string {
        $data = [
            'meta_title' => 'Materials',
            'title' => 'Materials',
            'filters' => $this->propertyGetter->getTypes()
        ];

        // echo '<pre>'; print_r($_POST); echo '</pre>';
        if ($this->request->getPost()) {
            $filters = [];
            foreach ($_POST as $k => $v) {
                if ($k == 'search') continue;
                $filters[$k] = $v;
            }
            $data['posts'] = $this->postModel->filter($_POST['search'], $filters);
        } else {
            $data['posts'] = $this->postModel->findAll();
        }

        // echo '<pre>';
        // print_r($data['posts'][0]->properties);
        // echo '</pre>';

        return view('post_view_all', $data);
    }

    public function search() {
        $this->load->view('search');
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

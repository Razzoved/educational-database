<?php declare(strict_types = 1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Entities\User as EntitiesUser;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Psr\Log\LoggerInterface;

class User extends BaseController
{
    private UserModel $users;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->users = model(UserModel::class);
    }

    public function index() : string
    {
        if (!$this->request->getPost('sort')) {
            $_POST['sort'] = 'name';
        }

        $data = [
            'meta_title' => 'Administration - Users',
            'title'      => 'User editor',
            'options'    => $this->getOptions(),
            'users'      => $this->getUsers(Config::PAGE_SIZE),
            'pager'      => $this->users->pager,
            'activePage' => 'users',
        ];

        return view(Config::VIEW . 'user/table', $data);
    }

    public function save() : void
    {
        $user = new EntitiesUser($this->request->getPost());

        $rules = [
            'id'              => 'required|is_not_unique[users.user_id]',
            'name'            => 'required|min_length[2]|max_length[50]',
            'email'           => 'required|min_length[4]|max_length[320]|valid_email',
            'password'        => 'required|min_length[4]|max_length[50]',
            'confirmPassword' => 'matches[password]'
        ];

        if (!$user->id) {
            unset($rules['id']);
            $rules['email'] .= '|is_unique[users.user_email]';
        }

        if (!$user->password) {
            unset($rules['password']);
            unset($rules['confirmPassword']);
        }

        if (!$this->validate($rules)) {
            $this->response->setStatusCode(Response::HTTP_BAD_REQUEST);
            echo view('errors/error_modal', [
                'title'   => 'Saving of user failed',
                'message' => array_values($this->validator->getErrors())[0],
            ]);
            return;
        }

        try {
            $this->users->save($user);
            if (!$user->id) {
                $user = $this->users->find($user->id);
            }
            if (is_null($user)) {
                throw new Exception('NOT FOUND');
            }
        } catch (Exception $e) {
            $this->response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            echo view('errors/error_modal', [
                'title'   => 'Saving of user failed',
                'message' => 'Could not ' . ($user->id ? 'update' : 'create') . ' the user, please try again later!',
            ]);
            return;
        }

        echo json_encode($user);
    }

    public function create() : void
    {
        echo view(Config::VIEW . 'user/form');
    }

    public function get(int $id) : void
    {
        $user = $this->users->get($id);
        if ($user === null) {
            $this->response->setStatusCode(Response::HTTP_NOT_FOUND);
            echo view('errors/error_modal', [
                'title'   => 'User: ' . $id,
                'message' => 'The user does not exist!'
            ]);
        } else {
            echo view(Config::VIEW . 'user/form', [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ]);
        }
    }

    public function delete(int $id) : void
    {
        $user = $this->users->get($id);
        if ($user === null) {
            $this->response->setStatusCode(Response::HTTP_NOT_FOUND);
            echo view('errors/error_modal', [
                'title'   => 'User: ' . $id,
                'message' => 'The user does not exist!'
            ]);
            return;
        }

        // cannot delete self
        if (session('user')->id === $user->id) {
            $this->response->setStatusCode(Response::HTTP_BAD_REQUEST);
            echo view('errors/error_modal', [
                'title' => 'User: ' . $id,
                'message' => 'You cannot delete yourself!'
            ]);
            return;
        }

        try {
            $this->users->delete($user->id);
        } catch (Exception $e) {
            $this->response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            echo view('errors/error_modal', [
                'title' => 'User: ' . $id,
                'message' => 'Could not delete ' . $user->name . '. Please try again later!'
            ]);
            return;
        }

        echo json_encode($id);
    }

    private function getOptions() : array
    {
        $result = [];

        foreach ($this->users->getArray(['sort' => 'name']) as $user) {
            $result[] = $user->name;
            $result[] = $user->email;
        }

        return $result;
    }

    protected function getUsers(int $perPage = 20) : array
    {
        return $this->users->getPage(
            (int) $this->request->getGetPost('page') ?? 1 ,
            [
                'search'    => $this->request->getGetPost('search'),
                'sort'      => $this->request->getGetPost('sort'),
                'sortDir'   => $this->request->getGetPost('sortDir'),
            ],
            $perPage
        );
    }
}

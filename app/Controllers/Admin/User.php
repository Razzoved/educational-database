<?php declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Entities\User as EntitiesUser;
use App\Models\UserModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Psr\Log\LoggerInterface;

class User extends ResponseController
{
    const DEFAULT_SORT = 'name';

    private UserModel $users;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->users = model(UserModel::class);
    }

    public function index(): string
    {
        $this->setSort('name');

        return $this->view('user/table', [
            'meta_title' => 'Administration - Users',
            'title'      => 'User editor',
            'options'    => $this->getOptions(),
            'users'      => $this->getUsers(ADMIN_PAGE_SIZE),
            'pager'      => $this->users->pager,
        ]);
    }

    /** ----------------------------------------------------------------------
     *                           AJAX HANDLERS
     *  ------------------------------------------------------------------- */

    public function save()
    {
        $user = new EntitiesUser($this->request->getPost());

        $rules = [
            'id'    => 'permit_empty|is_natural',
            'name'  => 'required|string|min_length[2]|max_length[50]',
            'email' => 'required|string|min_length[4]|max_length[320]|valid_email|user_unique_email[]',
        ];
        if (!$user->id || $this->request->getPost('changePassword') == true) {
            $rules['password'] = 'required|min_length[6]|max_length[50]';
            $rules['confirmPassword'] = 'required|matches[password]';
        }

        if (!$this->validate($rules)) {
            return $this->toResponse(
                $user,
                $this->validator->getErrors(),
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            if (!$this->users->save($user->toRawArray())) throw new Exception();
            if (!$user->id) $user->id = $this->users->getInsertID();
            unset($user->password);
            unset($user->confirmPassword);
        } catch (Exception $e) {
            return $this->toResponse(
                $user,
                ['database' => 'Saving failed, try again later!'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return $this->response->setJSON($user);
    }

    public function get(int $id): Response
    {
        $user = $this->users->find($id);
        if (!$user) {
            return $this->response->setStatusCode(
                Response::HTTP_NOT_FOUND,
                'User with id ' . $id . ' not found!'
            );
        }
        return $this->response->setJSON($user);
    }

    public function delete(int $id): Response
    {
        return $this->doDelete(
            $id,
            function ($i) {
                return $this->users->find($i);
            },
            function ($e) {
                if (session('user') && session('user')->id === $e->id) {
                    throw new Exception('cannot delete self');
                }
                $this->users->delete($e->id);
            },
            'user'
        );
    }

    /** ----------------------------------------------------------------------
     *                           HELPER METHODS
     *  ------------------------------------------------------------------- */

    protected function getOptions(): array
    {
        $result = [];

        foreach ($this->users->getArray(['sort' => 'name']) as $user) {
            $result[] = $user->name;
            $result[] = $user->email;
        }

        return $result;
    }

    protected function getUsers(int $perPage = 20): array
    {
        return $this->users->getPage(
            (int) $this->request->getGetPost('page') ?? 1,
            [
                'search'    => $this->request->getGetPost('search'),
                'sort'      => $this->request->getGetPost('sort') ?? self::DEFAULT_SORT,
                'sortDir'   => $this->request->getGetPost('sortDir'),
            ],
            $perPage
        );
    }
}

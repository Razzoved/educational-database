<?php declare(strict_types = 1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Entities\User as EntitiesUser;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Exception;
use Psr\Log\LoggerInterface;

class User extends BaseController
{
    private UserModel $users;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->users = model(UserModel::class);
    }

    public function logout() : RedirectResponse
    {
        session()->destroy();
        return redirect()->to(base_url());
    }

    public function index() : string
    {
        $data = [
            'meta_title' => 'Administration - Users',
            'title'      => 'User editor',
            'users'      => $this->getUsers(current_url(), Config::PAGE_SIZE),
            'pager'      => $this->users->pager,
        ];

        return view(Config::VIEW . 'user/table', $data);
    }

    /**
     * Ajax handler that prepares and echoes back a user.
     * Expects 'email' to be loaded into _GET.
     * Throws error if not found.
     */
    public function getUser() : void
    {
        $user = $this->users->find($this->request->getGet('email'));
        if ($user === null) {
            throw PageNotFoundException::forPageNotFound();
        }
        $user->password = '';
        echo json_encode($user);
    }

    /**
     * Ajax handler for updating user.
     * Expects 'name', 'email', 'password' to be loaded into _POST.
     *
     * @return void if successful, echoes back user
     */
    public function update() : void
    {
        $rules = [
            'name'            => 'required|min_length[2]|max_length[50]',
            'email'           => 'required|min_length[4]|max_length[320]|valid_email|is_not_unique[users.user_email]',
        ];

        if ($this->request->getPost('password') !== "") {
            $rules['password'] = 'required|min_length[4]|max_length[50]';
            $rules['confirmPassword'] = 'matches[password]';
        } else {
            $_POST['password'] = null;
        }

        $this->ajaxSave($rules, true);
    }

    /**
     * Ajax handler for creating user.
     * Expects 'name', 'email', 'password' to be loaded into _POST.
     *
     * @return void if successful, echoes back user
     */
    public function create() : void
    {
        $rules = [
            'name'            => 'required|min_length[2]|max_length[50]',
            'email'           => 'required|min_length[4]|max_length[320]|valid_email|is_unique[users.user_email]',
            'password'        => 'required|min_length[4]|max_length[50]',
            'confirmPassword' => 'matches[password]'
        ];

        $this->ajaxSave($rules, false);
    }

    /**
     * Ajax handler for deleting a user.
     * Expects validator to be loaded in _POST.
     * Expects 'email' to be loaded in either _POST or _GET.
     *
     * @return void if successful, echoes back user, else echoes back modal
     */
    public function delete() : void
    {
        if (!$this->checkAjax()) return;

        if (!$this->validate(['email' => "required|is_not_unique[users.user_email]"])) {
            $this->response->setStatusCode(Response::HTTP_NOT_ACCEPTABLE, 'Delete validation failed')
                           ->setJSON($this->validator->listErrors())
                           ->send();
            return;
        }

        $email = $this->request->getPost('email') ?? "";
        try {
            $this->users->delete($email);
        } catch (Exception $e) {
            $this->response->setStatusCode(Response::HTTP_PRECONDITION_FAILED, $e->getMessage())
                           ->setJSON($email)
                           ->send();
            return;
        }
        echo json_encode($email);
    }

        /**
     * Convenience method that handles ajax requests to save into database.
     * May result in exception when saving.
     *
     * @param array $rules what to check in post data
     * @param bool $isUpdate wheter to update or insert
     */
    private function ajaxSave(array $rules, bool $isUpdate = false) : void
    {
        if (!$this->request->isAJAX()) {
            $this->response->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED, 'Must be an AJAX request')
                           ->send();
            return;
        }

        if (!$this->validate($rules)) {
            $this->response->setStatusCode(Response::HTTP_NOT_ACCEPTABLE, 'User validation failed')
                           ->setJSON($this->validator->listErrors())
                           ->send();
            return;
        }

        $user = null;
        try {
            $user = $this->save($isUpdate);
        } catch (Exception $e) {
            $this->response->setStatusCode(Response::HTTP_BAD_REQUEST, $e->getMessage())
                           ->send();
            return;
        }

        if (is_null($user)) {
            $this->response->setStatusCode(Response::HTTP_BAD_REQUEST, "User saving failed")
                           ->send();
            return;
        }

        echo json_encode($user, JSON_FORCE_OBJECT);
    }

    /**
     * Convenience method that handles saving to database.
     * May result in exception when saving.
     *
     * @param bool $isUpdate wheter to update or insert
     * @return ?EntitiesUser object representing the posted data or null
     */
    private function save(bool $isUpdate = false) : ?EntitiesUser
    {
        $data = [
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
        ];

        // do not overwrite password if not changed
        $password = $this->request->getPost('password');
        if ($password !== "" && $password !== null) {
            $data['password'] = $password;
        }

        $user = new EntitiesUser($data);
        if ($isUpdate) {
            $this->users->update($user->email, $user);
        } else {
            $this->users->insert($user);
        }

        $user = $this->users->find($user->email);
        $user->password = '';
        return $user;
    }

    private function getUsers(string $url, int $perPage = 10) : array
    {
        $uri = new \CodeIgniter\HTTP\URI($url);
        return $this->loadUsers()
                    ->paginate($perPage, 'default', null, $uri->getTotalSegments());
    }

    private function loadUsers(): UserModel
    {
        $sort = $this->request->getPost('sort');
        $sortDir = $this->request->getPost('sortDir');
        $search = $this->request->getPost('search') ?? "";

        return ($search !== "")
            ? $this->users->getByFilters($sort, $sortDir, $search)
            : $this->users->getData($sort, $sortDir);
    }

    private function checkAjax() : bool
    {
        if (!$this->request->isAJAX()) {
            $this->response->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED)->send();
            return false;
        }
        return true;
    }
}

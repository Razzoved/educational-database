<?php declare(strict_types = 1);

namespace App\Controllers;

use App\Models\UserModel;

class Authentication extends BaseController
{
    protected array $rules = [
        'email'    => 'required|string|user_email',
        'password' => 'required|string|user_password[{email}]',
    ];

    public function index() : string
    {
        return $this->view('user_login');
    }

    public function login()
    {
        if (!$this->validate($this->rules)) {
            return $this->view('user_login', ['errors' => $this->validator->getErrors()]);
        }

        $user = model(UserModel::class)->getEmail($this->request->getPost('email') ?? "");
        unset($user->password);

        session()->set([
            'user' => $user,
            'isLoggedIn' => true,
        ]);

        return previous_url() === url_to('Authentication::login')
            ? redirect()->to(url_to('Admin\Dashboard::index'))
            : redirect()->to(previous_url());
    }

    public function logout()
    {
        if (session()->has('isLoggedIn') && session()->get('isLoggedIn') === true) {
            session()->remove('user');
            session()->remove('isLoggedIn');
        }
        return redirect()->to(url_to('Material::index'));
    }
}

<?php declare(strict_types = 1);

namespace App\Controllers;

use App\Models\UserModel;

class Authentication extends BaseController
{
    protected array $settings = [
        'email' => [
            'rules'  => 'required|valid_email|user_email',
            'errors' => [
                'user_email' => 'Invalid email.'
            ],
        ],
        'password' => [
            'rules'  => 'required|user_password[{email}]',
            'errors' => [
                'user_password' => 'Invalid password.'
            ],
        ],
    ];

    public function index() : string
    {
        return $this->view('user_login');
    }

    public function login()
    {
        if (!$this->validate($this->settings)) {
            return $this->view('user_login', ['errors' => $this->validator->getErrors()]);
        }

        $user = model(UserModel::class)->getEmail($this->request->getPost('email') ?? "");

        $user->password = null;
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

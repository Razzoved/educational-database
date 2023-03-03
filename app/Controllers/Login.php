<?php declare(strict_types = 1);

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\Config\Services;

class Login extends BaseController
{
    protected array $messages = [
        'email'    => ['validUserEmail'    => 'Invalid email.'],
        'password' => ['validUserPassword' => 'Invalid password.'],
    ];

    public function index() : string
    {
        return view(
            'user_login',
            ['validation' => Services::validation()]
        );
    }

    public function authenticate() : mixed
    {
        $user = model(UserModel::class)->getByEmail($this->request->getVar('email'));
        $email = is_null($user) ? null : $user->email;
        $password = is_null($user) ? null : $user->password;

        $rules = [
            'email'    => "required|validUserEmail[{$email}]",
            'password' => "required|validUserPassword[{$password}]",
        ];

        if (!$this->validate($rules, $this->messages)) {
            return view(
                'user_login',
                ['validation' => $this->validator]
            );
        }

        $session_data = [
            'user' => $user,
            'isLoggedIn' => true,
        ];
        session()->set($session_data);

        return redirect()->to('/admin');
    }
}

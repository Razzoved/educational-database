<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\UserModel;

class Authentication extends DefaultController
{
    protected array $rules = [
        'email'    => 'required|string|user_email',
        'password' => 'required|string|user_password[{email}]',
    ];

    public function index(): string
    {
        return $this->view('user_login');
    }

    public function login()
    {
        if (!$this->validate($this->rules)) {
            return $this->view('user_login', ['errors' => $this->validator->getErrors()]);
        }

        $user = model(UserModel::class)->findEmail($this->validator->getValidated()['email'] ?? "");
        unset($user->password);

        session()->set([
            'user' => $user,
            'isLoggedIn' => true,
        ]);
        session()->remove("reset[{$user->id}]");

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

    /**
     * Creates a new token and saves it to uses's session for reset use.
     * Shows a reset view to the user.
     *
     * @param string $email The email address to send token to.
     */
    public function reset(string $email): string
    {
        $user = model(UserModel::class)->findEmail($email);
        unset($user->password);

        if (!$user) {
            $this->request->setGlobal('post', ['email' => $email]);
            return $this->view('user_login', [
                'errors' => array('No user exists with this email address!')
            ]);
        }

        $token = hash('sha256', uniqid((string) mt_rand(), true));

        $msg = \Config\Services::email();
        $msg->setFrom('materials@academicintegrity.eu', 'Academic Integrity');
        $msg->setTo($email);
        $msg->setSubject("Password reset");
        $msg->setMessage(
            "Hello {$user->name}!{$msg->newline}" .
                "{$msg->newline}" .
                "--------- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -{$msg->newline}" .
                "| TOKEN | {$token}{$msg->newline}" .
                "--------- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -{$msg->newline}" .
                "{$msg->newline}" .
                "This token will work until another email is sent or your session runs out."
        );
        $msg->send();

        session()->set("reset[{$user->id}]", $token);

        $this->request->setGlobal('post', ['id' => $user->id]);
        return $this->view('user_reset');
    }

    /**
     * Tries to submit the password change. Runs various checks
     * before changing the password.
     */
    public function resetSubmit()
    {
        $rules = [
            'id'              => 'required|is_natural',
            'token'           => 'required|string',
            'password'        => 'required|min_length[6]|max_length[50]',
            'confirmPassword' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return $this->view('user_reset', [
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = $this->validator->getValidated();
        $id = $data['id'];

        // verify against session
        $reset = session()->get("reset[{$id}]");
        if (!isset($reset) || $data['token'] !== $reset) {
            return $this->view('user_reset', [
                'errors' => array('Invalid token!')
            ]);
        }

        // save data and clear session
        try {
            model(UserModel::class)->update($id, [
                'user_password' => $data['password'],
            ]);
            session()->remove("reset[{$id}]");
        } catch (\Exception $e) {
            return $this->view('user_reset', [
                'errors' => array('Could not update password')
            ]);
        }

        return redirect()->to(url_to('Admin\Dashboard::index'));
    }
}

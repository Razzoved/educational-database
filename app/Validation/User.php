<?php declare(strict_types = 1);

namespace App\Validation;

class User
{
    public function validUserEmail(string $email) : bool
    {
        return model(UserModel::class)->getByEmail($email) !== null;
    }


    public function validUserPassword(string $password, string $email) : bool
    {
        $user = model(UserModel::class)->getByEmail($email);
        return $user !== null && password_verify($password, $user->password);
    }
}

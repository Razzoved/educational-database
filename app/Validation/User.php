<?php

declare(strict_types=1);

namespace App\Validation;

class User
{
    public function user_name_update(string $name, string $params, array $data): bool
    {
        $users = model(UserModel::class)->where('user_name', $name)->findAll(2);
        $count = sizeof($users);
        return $count === 0 || ($count === 1 && isset($data[$params]) && $users[0]->id === (int) $data[$params]);
    }

    public function user_email_update(string $email, string $params, array $data): bool
    {
        $users = model(UserModel::class)->where('user_email', $email)->findAll(2);
        $count = sizeof($users);
        return $count === 0 || ($count === 1 && isset($data[$params]) && $users[0]->id === (int) $data[$params]);
    }

    public function user_email(string $email): bool
    {
        return model(UserModel::class)->where('user_email', $email)->first() !== null;
    }

    public function user_password(string $password, string $email): bool
    {
        $user = model(UserModel::class)->where('user_email', $email)->allowCallbacks(false)->first();
        return $user && password_verify($password, $user->password);
    }
}

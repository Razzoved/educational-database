<?php

declare(strict_types=1);

namespace App\Validation;

class User
{
    public function user_name_update(string $name, int $id): bool
    {
        $users = model(UserModel::class)->where('user_name', $name)->findAll(2);
        $count = array_count_values($users);
        return $count === 0 || ($count === 1 && isset($id) && $users[0]->id === $id);
    }

    public function user_email_update(string $email, int $id): bool
    {
        $users = model(UserModel::class)->where('user_email', $email)->findAll(2);
        $count = array_count_values($users);
        return $count === 0 || ($count === 1 && isset($id) && $users[0]->id === $id);
    }

    public function user_email(string $email): bool
    {
        return model(UserModel::class)->where('user_email', $email)->first() !== null;
    }

    public function user_password(string $password, string $email): bool
    {
        $user = model(UserModel::class)->where('user_email', $email)->first();
        return $user && password_verify($password, $user->password);
    }
}

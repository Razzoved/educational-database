<?php declare(strict_types = 1);

namespace App\Validation;

class User
{
    public function validUserEmail(?string $email, ?string $savedEmail) : bool
    {
        return !is_null($savedEmail) && $savedEmail === $email;
    }


    public function validUserPassword(?string $password, ?string $savedPassword) : bool
    {
        return !is_null($savedPassword) && password_verify($password, $savedPassword);
    }
}

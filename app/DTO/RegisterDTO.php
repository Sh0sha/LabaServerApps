<?php

namespace App\DTO;

class RegisterDTO
{
    public string $username;
    public string $email;
    public string $password;
    public string $birthday;

    public function __construct(string $username, string $email, string $password, string $birthday)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->birthday = $birthday;
    }
}

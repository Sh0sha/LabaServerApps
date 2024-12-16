<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Seeder
{
    public function run()
    {
        User::firstOrCreate(
            ['email' => "admin@mail.ru"],
            [
                'username' => "AdminAdmin",
                'password' => Hash::make("Password123!"),
                'birthday' => "1999-06-24",
            ]
        );
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
              'username' => 'admin',
              'email' => session('email'),
              'password' => Hash::make(session('password')),
              'role' => 'admin',
              'api_token' => Str::random(60)
          ]);
    }
}

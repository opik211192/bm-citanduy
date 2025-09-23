<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'name' => 'User1',
            'username' => 'user1',
            'email' => 'user1@gmail.com',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'name' => 'User2',
            'username' => 'user2',
            'email' => 'user2@gmail.com',
            'password' => bcrypt('password'),
        ]);
    }
}

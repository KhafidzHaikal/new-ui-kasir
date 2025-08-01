<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = array(
            [
                'name' => 'Administrator',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('123'),
                'foto' => '/img/user.jpg',
                'level' => 1
            ],
            [
                'name' => 'Admin Waserda 1',
                'email' => 'waserda@gmail.com',
                'password' => bcrypt('123'),
                'foto' => '/img/user.jpg',
                'level' => 2
            ],
            [
                'name' => 'Gudang 1',
                'email' => 'gudang@gmail.com',
                'password' => bcrypt('123'),
                'foto' => '',
                'level' => 3
            ],
            [
                'name' => 'Bengkel 1',
                'email' => 'bengkel@gmail.com',
                'password' => bcrypt('123'),
                'foto' => '',
                'level' => 4
            ],
            [
                'name' => 'Fotocopy 1',
                'email' => 'fc@gmail.com',
                'password' => bcrypt('123'),
                'foto' => '',
                'level' => 5
            ],
            [
                'name' => 'Kasir',
                'email' => 'kasir@gmail.com',
                'password' => bcrypt('123'),
                'foto' => '',
                'level' => 6
            ],
            [
                'name' => 'USP Admin',
                'email' => 'usp@gmail.com',
                'password' => bcrypt('123'),
                'foto' => '',
                'level' => 7
            ],
            [
                'name' => 'FC Dinas',
                'email' => 'dinas@gmail.com',
                'password' => bcrypt('123'),
                'foto' => '',
                'level' => 8
            ],
        );

        array_map(function (array $user) {
            User::query()->updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }, $users);
    }
}

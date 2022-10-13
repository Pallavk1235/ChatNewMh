<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::insert([
        [
            'name' => 'Jhon',
            'email' => 'jhon@mail.com',
            'password' => bcrypt('123456'),
        ],[
            'name' => 'Lonardo',
            'email' => 'Lonardo@mail.com',
            'password' => bcrypt('123456'),
        ],[
            'name' => 'Messi',
            'email' => 'Messi@mail.com',
            'password' => bcrypt('123456'),
        ],[
            'name' => 'Paddy',
            'email' => 'Paddy@mail.com',
            'password' => bcrypt('123456'),
        ],[
            'name' => 'Brijs',
            'email' => 'Brijs@mail.com',
            'password' => bcrypt('123456'),
        ],[
            'name' => 'heets',
            'email' => 'heets@mail.com',
            'password' => bcrypt('123456'),
        ],[
            'name' => 'rocky',
            'email' => 'rocky@mail.com',
            'password' => bcrypt('123456'),
        ]]);
    }
}

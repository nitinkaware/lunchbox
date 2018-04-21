<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::unguard();

        User::truncate();

        $password = bcrypt('secret');

        User::create([
            'name'     => 'Nitin Kaware',
            'email'    => 'nitin.kaware1@gmail.com',
            'password' => $password,
        ]);

        User::create([
            'name'     => 'Shantanu Puranik',
            'email'    => 'shantanu.puranik@gmail.com',
            'password' => $password,
        ]);

        User::create([
            'name'     => 'Praveen Reddy',
            'email'    => 'lekkalea08@gmail.com',
            'password' => $password,
        ]);

        User::create([
            'name'     => 'Dharmesh Bhatt',
            'email'    => 'dharmesh.bhatt@gmail.com',
            'password' => $password,
        ]);

        User::create([
            'name'     => 'Steven Joseph',
            'email'    => 'steven.joseph@gmail.com',
            'password' => $password,
        ]);
    }
}

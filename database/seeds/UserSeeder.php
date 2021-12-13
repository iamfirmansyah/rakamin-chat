<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
        	'id'        => 'c3d8-efgh',
        	'username'  => 'joni',
        	'fullname'  => 'Joni Tea',
        	'email'     => 'joni@gmail.com',
        	'password'  => Hash::make('admin1234'),
            'phone'     => '08123456'
        ]);

        User::create([
        	'id'        => 'asdf-efgh',
        	'username'  => 'ahmad',
        	'fullname'  => 'Ahmad Daffa',
        	'email'     => 'ahmad@gmail.com',
        	'password'  => Hash::make('admin1234'),
            'phone'     => '0891231'
        ]);

        User::create([
        	'id'        => 'qwer-efgh',
        	'username'  => 'sae',
        	'fullname'  => 'Sae Meong',
        	'email'     => 'sae@gmail.com',
        	'password'  => Hash::make('admin1234'),
            'phone'     => '0898989'
        ]);
    }
}

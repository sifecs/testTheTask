<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Сергей',
            'email' => 'admin@mail.ru',
            'password' => bcrypt('admin'),
        ]);
    }
}

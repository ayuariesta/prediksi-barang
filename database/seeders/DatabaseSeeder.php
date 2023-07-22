<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'username' => 'ayuariesta5',
            'firstname' => 'Ayu',
            'lastname' => 'Ariesta',
            'email' => 'ayuariesta71@gmail.com',
            'password' => bcrypt('prediksiAyu')
        ]);
    }
}

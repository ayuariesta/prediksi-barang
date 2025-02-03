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
            'username' => 'ariestaayuu5',
            'nama_lengkap' => 'Ayu Ariesta',
            'password' => bcrypt('ayuariesta')
        ]);
        
    }
}

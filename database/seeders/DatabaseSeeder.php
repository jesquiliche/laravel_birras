<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ColoresSeeder::class);
        $this->call(TiposSeeder::class);
        $this->call(GraduacionesSeeder::class);
        $this->call(PaisesSeeder::class);
        $this->call(CervezasSeeder::class);
        $this->call(UsersSeeder::class);
    }
}

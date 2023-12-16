<?php

namespace Database\Seeders;

use App\Models\Pacote;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PacotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Pacote::factory()->count(10)->create();
    }
}

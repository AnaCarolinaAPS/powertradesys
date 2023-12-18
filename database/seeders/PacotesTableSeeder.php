<?php

namespace Database\Seeders;

use App\Models\Pacote;
use App\Models\Shipper;
use App\Models\Embarcador;
use App\Models\Warehouse;
use App\Models\Despachante;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PacotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criação de um novo Shipper no banco de dados
        $shipper1 = Shipper::create([
            'name' => 'VARIOS SHIPPERS',
        ]);

        $shipper2 = Shipper::create([
            'name' => 'VICTORIA TECH INC',
        ]);

        // Criação de um novo Embarcador no banco de dados
        $embarcador1 = Embarcador::create([
            'nome' => 'Peniel International',
            'contato' => '+1',
        ]);

        // Criação de um novo Embarcador no banco de dados
        $embarcador2 = Embarcador::create([
            'nome' => 'Transway - Air & Ocean Freight',
            'contato' => '+1',
        ]);

        // Criação de um novo Shipper no banco de dados
        $warehouse1 = Warehouse::create([
            'wr' => '74',
            'data' => Carbon::now()->toDateString(), // Aqui atribuímos a data de hoje ao campo 'data'
            'shipper_id' => $shipper1->id,
            'embarcador_id' => $embarcador1->id,
        ]);

        // Criação de um novo Shipper no banco de dados
        $warehouse2 = Warehouse::create([
            'wr' => '75',
            'data' => Carbon::now()->toDateString(), // Aqui atribuímos a data de hoje ao campo 'data'
            'shipper_id' => $shipper2->id,
            'embarcador_id' => $embarcador2->id,
        ]);

        Pacote::factory()->count(20)->create();

        // Criação de um novo Despachante no banco de dados
        $despachante = Despachante::create([
            'nome' => 'Heriberto Guerrero',
            'contato' => '+595',
        ]);

        // Criação de um novo Despachante no banco de dados
        $despachante = Despachante::create([
            'nome' => 'Adrían',
            'contato' => '+595',
        ]);
    }
}

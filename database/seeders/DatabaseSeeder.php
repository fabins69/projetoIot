<?php

namespace Database\Seeders;

use App\Models\Ambiente;
use App\Models\Sensor;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        Ambiente::create([
            'nome' => 'Sala de aula',
            'descricao' => 'salinha',
            'status' => true
        ]);
        Ambiente::create([
            'nome' => 'Laboratorio',
            'descricao' => 'LAB',
            'status' => false
        ]);
        Ambiente::create([
            'nome' => 'Escola',
            'descricao' => 'sesi',
            'status' => false
        ]);


        // Sensores
        Sensor::create([
            'ambiente_id' => 1,
            'codigo' => 'TEMP-01',
            'tipo' => 'temperatura',
            'descricao' => 'sensor 1',
            'status' => true
        ]);
        Sensor::create([
            'ambiente_id' => 2,
            'codigo' => 'TEMP-02',
            'tipo' => 'temperatura',
            'descricao' => 'sensor 2',
            'status' => false
        ]);
        Sensor::create([
            'ambiente_id' => 3,
            'codigo' => 'TEMP-03',
            'tipo' => 'temperatura',
            'descricao' => 'sensor 3',
            'status' => true
        ]);
    }
}

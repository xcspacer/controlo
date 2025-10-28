<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Station;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Station::create([
            'name' => 'Alcobaça',
            'group' => '4',
            'address' => 'Rua João Casimiro, 2460-312 Pinhal Fanheiro - Bárrio',
            'city' => 'Alcobaça',
            'is_active' => true,
        ]);

        Station::create([
            'name' => 'Arranhó',
            'group' => '1',
            'address' => 'Estrada Nacional n.º 115, 2630-058 Arranhó',
            'city' => 'Arranhó',
            'is_active' => true,
        ]);

        Station::create([
            'name' => 'Benedita',
            'group' => '4',
            'address' => 'Estrada Nacional N 8, 6 84, 2475-034 Taveiro, Benedita',
            'city' => 'Benedita',
            'is_active' => true,
        ]);

        Station::create([
            'name' => 'Belinho',
            'group' => '3',
            'address' => 'Avenida do Belinho, nº64 Belinho, 4740-161 Esposende',
            'city' => 'Belinho',
            'is_active' => true,
        ]);

        Station::create([
            'name' => 'Boleiros',
            'group' => '2',
            'address' => 'Estrada de Minde n.º 328, 2495-300 Boleiros, Fátima',
            'city' => 'Boleiros',
            'is_active' => true,
        ]);

        Station::create([
            'name' => 'Braga',
            'group' => '3',
            'address' => 'N309 34, 4705-104 Braga',
            'city' => 'Braga',
            'is_active' => true,
        ]);

        Station::create([
            'name' => 'Casal Ribeiro',
            'group' => '2',
            'address' => 'Rua da Ponte n.º 30, 2435-522 Rio de Couros, Casal Ribeiro',
            'city' => 'Casal Ribeiro',
            'is_active' => true,
        ]);

        Station::create([
            'name' => 'Fajarda',
            'group' => '1',
            'address' => 'Estrada nacional 114 - 3Km 6,5 Sul 2100-502 Fajarda, Coruche',
            'city' => 'Fajarda',
            'is_active' => true,
        ]);

        Station::create([
            'name' => 'Fazendas de Almeirim',
            'group' => '1',
            'address' => 'Rua Marechal Craveiro Lopes n.º 168, 2080-215 Fazendas',
            'city' => 'Fazendas de Almeirim',
            'is_active' => true,
        ]);

        Station::create([
            'name' => 'Fervença',
            'group' => '2',
            'address' => 'Rua Prof. Adelino Rodrigues n.º 24, 2460-526 Maiorga',
            'city' => 'Fervença',
            'is_active' => true,
        ]);

        Station::create([
            'name' => 'Leiria',
            'group' => '2',
            'address' => 'Rua do Forno da Telha n.º 1, 2410-023 Barreira',
            'city' => 'Leiria',
            'is_active' => true,
        ]);

        Station::create([
            'name' => 'Mendiga',
            'group' => '4',
            'address' => 'Rua Principal n.º 95 2480-215 Mendiga',
            'city' => 'Mendiga',
            'is_active' => true,
        ]);

        Station::create([
            'name' => 'Sabacheira',
            'group' => '2',
            'address' => 'N113 Chão de Maçãs Gare, n.º 80, 2305-613 Chão de Maçãs Gare',
            'city' => 'Sabacheira',
            'is_active' => true,
        ]);

        Station::create([
            'name' => 'São Mamede',
            'group' => '2',
            'address' => 'Estrada Principal de Mira Aire n.º 4 2495-032 São Mamede',
            'city' => 'São Mamede',
            'is_active' => true,
        ]);

        Station::create([
            'name' => 'Ribeira dos Amiais',
            'group' => '4',
            'address' => 'Estrada Municipal 567 Alvorninha  2500-379 Zambujal',
            'city' => 'Ribeira dos Amiais',
            'is_active' => true,
        ]);

        Station::create([
            'name' => 'Vandoma',
            'group' => '3',
            'address' => 'Av.Central de Reiros, n.º 1187 4585-774 Vandoma',
            'city' => 'Vandoma',
            'is_active' => true,
        ]);

        Station::create([
            'name' => 'Vidais',
            'group' => '4',
            'address' => 'N114 KM 36, 2500-749 Vidais',
            'city' => 'Vidais',
            'is_active' => true,
        ]);
    }
}
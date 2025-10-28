<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => '3DKubic',
            'email' => 'geral@3dkubic.com',
            'password' => Hash::make('280522'),
            'is_admin' => true,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Paulo Morais',
            'email' => 'pmorais@gestroilenergy.com',
            'password' => Hash::make('pmorais2025'),
            'is_admin' => true,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Gonçalo Amorim',
            'email' => 'gamorim@hugarogroup.com',
            'password' => Hash::make('gamorim2025'),
            'is_admin' => true,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Gabriel Galvez',
            'email' => 'ceo@hugarogroup.com',
            'password' => Hash::make('ceo2025'),
            'is_admin' => true,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Sónia Rodrigues',
            'email' => 'srodrigues@gestroilenergy.com',
            'password' => Hash::make('srodrigues2025'),
            'is_admin' => true,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Maria Teresa Ferreira de Oliveira',
            'email' => 'moliveira@gestroilenergy.com',
            'password' => Hash::make('moliveira2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Viktoriya Kozhushna',
            'email' => 'vkozhushna@gestroilenergy.com',
            'password' => Hash::make('vkozhushna2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Oksana Ponomarenko',
            'email' => 'oponomarenko@gestroilenergy.com',
            'password' => Hash::make('oponomarenko2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Lyudmyla Bodnar',
            'email' => 'lbodnar@gestroilenergy.com',
            'password' => Hash::make('lbodnar2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Dulce Amado Anastácio',
            'email' => 'danastácio@gestroilenergy.com',
            'password' => Hash::make('danastácio2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Sofia Isabel Dias da Silva',
            'email' => 'ssilva@gestroilenergy.com',
            'password' => Hash::make('ssilva2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Carina Alexandra Borges Rodrigues',
            'email' => 'crodrigues@gestroilenergy.com',
            'password' => Hash::make('crodrigues2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Ricardo Jorge de Sousa Fernandes',
            'email' => 'rfernandes@gestroilenergy.com',
            'password' => Hash::make('rfernandes2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Yaismi de las Mercedes Goiburo Hernandez',
            'email' => 'yhernandez@gestroilenergy.com',
            'password' => Hash::make('yhernandez2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Maria Rosa Reis Lopes',
            'email' => 'mlopes@gestroilenergy.com',
            'password' => Hash::make('mlopes2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Patrícia Regina Souza D\'Avila Pinho',
            'email' => 'ppinho@gestroilenergy.com',
            'password' => Hash::make('ppinho2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Rafaela Alexandra Abreu Miranda Tomás',
            'email' => 'rtomás@gestroilenergy.com',
            'password' => Hash::make('rtomás2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Emanuel José Ferreira Claro',
            'email' => 'eclaro@gestroilenergy.com',
            'password' => Hash::make('eclaro2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Joana Gonçalves de Oliveira',
            'email' => 'jgoncalves@gestroilenergy.com',
            'password' => Hash::make('jgoncalves2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Tânia Filipa Leonor',
            'email' => 'tleonor@gestroilenergy.com',
            'password' => Hash::make('tleonor2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Rúben Filipe Santos Marques',
            'email' => 'rmarques@gestroilenergy.com',
            'password' => Hash::make('rmarques2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Vanilda da Rocha',
            'email' => 'vrocha@gestroilenergy.com',
            'password' => Hash::make('vrocha2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Samuel de Jesus Marques Glória',
            'email' => 'sglória@gestroilenergy.com',
            'password' => Hash::make('sglória2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Paulo Roberto Tartarine',
            'email' => 'ptartarine@gestroilenergy.com',
            'password' => Hash::make('ptartarine2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Rita Susana da Silva Coutinho',
            'email' => 'rcoutinho@gestroilenergy.com',
            'password' => Hash::make('rcoutinho2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Carla Sofia Lopes Andrade',
            'email' => 'candrade@gestroilenergy.com',
            'password' => Hash::make('candrade2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Adélia Cristiana da Conceição Venâncio',
            'email' => 'avenâncio@gestroilenergy.com',
            'password' => Hash::make('avenâncio2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Nathalia Viana Lacerdo',
            'email' => 'nlacerdo@gestroilenergy.com',
            'password' => Hash::make('nlacerdo2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Lais Lima de Camargo Souza',
            'email' => 'lsouza@gestroilenergy.com',
            'password' => Hash::make('lsouza2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Vítor Manuel Caetano Tavares',
            'email' => 'vtavares@gestroilenergy.com',
            'password' => Hash::make('vtavares2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Anabela Couto Nascimento Valentim',
            'email' => 'avalentim@gestroilenergy.com',
            'password' => Hash::make('avalentim2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Paula Maria Leal dos Santos Cipriano',
            'email' => 'pcipriano@gestroilenergy.com',
            'password' => Hash::make('pcipriano2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Silvia Seixas Rosa',
            'email' => 'srosa@gestroilenergy.com',
            'password' => Hash::make('srosa2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Svitlana Poberezhnyk',
            'email' => 'spoberezhnyk@gestroilenergy.com',
            'password' => Hash::make('spoberezhnyk2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Sandra Patrícia Ribeiro Duarte',
            'email' => 'sduarte@gestroilenergy.com',
            'password' => Hash::make('sduarte2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Isabel Maria Pinheiro',
            'email' => 'ipinheiro@gestroilenergy.com',
            'password' => Hash::make('ipinheiro2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Luciana Isabel Gonçalves Neto Pires',
            'email' => 'lpires@gestroilenergy.com',
            'password' => Hash::make('lpires2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Ilídio do Coito Morais',
            'email' => 'imorais@gestroilenergy.com',
            'password' => Hash::make('imorais2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Nicholas Valentim Xavier da Rocha Alvim',
            'email' => 'nalvim@gestroilenergy.com',
            'password' => Hash::make('nalvim2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Silvia da Conceição Ferreira',
            'email' => 'sferreira@gestroilenergy.com',
            'password' => Hash::make('sferreira2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Andreea Mihaela Costache',
            'email' => 'acostache@gestroilenergy.com',
            'password' => Hash::make('acostache2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Ana Filipa Lopes Olaia Baptista',
            'email' => 'abaptista@gestroilenergy.com',
            'password' => Hash::make('abaptista2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Bruce Ruúben Ferreira Teodoro',
            'email' => 'bteodoro@gestroilenergy.com',
            'password' => Hash::make('bteodoro2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Maria Aparecida de Souza de Oliveira',
            'email' => 'maparecida@gestroilenergy.com',
            'password' => Hash::make('maparecida2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Paulo Renato de Oliveira Gomes',
            'email' => 'pgomes@gestroilenergy.com',
            'password' => Hash::make('pgomes2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Pedro Filipe Meireles Marques',
            'email' => 'pmarques@gestroilenergy.com',
            'password' => Hash::make('pmarques2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Maria Monteiro Moura Costa',
            'email' => 'mcosta@gestroilenergy.com',
            'password' => Hash::make('mcosta2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Marlene Sofia da Silva Seabra',
            'email' => 'mseabra@gestroilenergy.com',
            'password' => Hash::make('mseabra2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Nadine dos Santos Pereira',
            'email' => 'npereira@gestroilenergy.com',
            'password' => Hash::make('npereira2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Teresa Manuela Madeira Gomes Ferraz',
            'email' => 'tferraz@gestroilenergy.com',
            'password' => Hash::make('tferraz2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Diana Manuela da Silva Ribeiro',
            'email' => 'dribeiro@gestroilenergy.com',
            'password' => Hash::make('dribeiro2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Maria Eduarda Soares Ferreira',
            'email' => 'mferreira@gestroilenergy.com',
            'password' => Hash::make('mferreira2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Norton Martins Gomes Neto',
            'email' => 'nneto@gestroilenergy.com',
            'password' => Hash::make('nneto2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Camila Rodrigues Soares Fraga',
            'email' => 'cfraga@gestroilenergy.com',
            'password' => Hash::make('cfraga2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Cristiana Pernadas da Silva',
            'email' => 'csilva@gestroilenergy.com',
            'password' => Hash::make('csilva2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Filomena Marques Jorge',
            'email' => 'fjorge@gestroilenergy.com',
            'password' => Hash::make('fjorge2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Manuela Maria da Silva Pereira',
            'email' => 'mpereira@gestroilenergy.com',
            'password' => Hash::make('mpereira2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Marta Isabel Bachá de Sousa',
            'email' => 'msousa@gestroilenergy.com',
            'password' => Hash::make('msousa2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Márcia Filipa da Silva Costa',
            'email' => 'mfilipa@gestroilenergy.com',
            'password' => Hash::make('mfilipa2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'David José Garcia Martins',
            'email' => 'dmartins@gestroilenergy.com',
            'password' => Hash::make('dmartins2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Auany Rodrigues Franzo',
            'email' => 'afranzo@gestroilenergy.com',
            'password' => Hash::make('afranzo2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Andreia Filipa de Oliveira Fernandes',
            'email' => 'afernandes@gestroilenergy.com',
            'password' => Hash::make('afernandes2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Vera Lídia Viana Meira da Cruz',
            'email' => 'vcruz@gestroilenergy.com',
            'password' => Hash::make('vcruz2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Maria Arlete Viana Torres Neiva',
            'email' => 'mneiva@gestroilenergy.com',
            'password' => Hash::make('mneiva2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Tiago Manuel Maciel Teixeira',
            'email' => 'tteixeira@gestroilenergy.com',
            'password' => Hash::make('tteixeira2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Joana Maria Rodrigues da Silva',
            'email' => 'jsilva@gestroilenergy.com',
            'password' => Hash::make('jsilva2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Clara Viana Eiras',
            'email' => 'ceiras@gestroilenergy.com',
            'password' => Hash::make('ceiras2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Maria Rosete Pires da Cunha',
            'email' => 'mcunha@gestroilenergy.com',
            'password' => Hash::make('mcunha2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Irene Torres Dias',
            'email' => 'idias@gestroilenergy.com',
            'password' => Hash::make('idias2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Mikaele Oliveira Duarte',
            'email' => 'mduarte@gestroilenergy.com',
            'password' => Hash::make('mduarte2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Silvia Isabel Narciso Antunes',
            'email' => 'santunes@gestroilenergy.com',
            'password' => Hash::make('santunes2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Ana Caroline Rodrigues Guimarães',
            'email' => 'aguimarães@gestroilenergy.com',
            'password' => Hash::make('aguimarães2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Sara Sousa Dos Santos',
            'email' => 'ssantos@gestroilenergy.com',
            'password' => Hash::make('ssantos2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Carina Oleksteievgch',
            'email' => 'coleksteievgch@gestroilenergy.com',
            'password' => Hash::make('coleksteievgch2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Jessica Sofia Matos Vitorino',
            'email' => 'jvitorino@gestroilenergy.com',
            'password' => Hash::make('jvitorino2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Jasminy Eishella Barbosa Ferreira',
            'email' => 'jferreira@gestroilenergy.com',
            'password' => Hash::make('jferreira2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Erica Andreia de Barros Barroso',
            'email' => 'ebarroso@gestroilenergy.com',
            'password' => Hash::make('ebarroso2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Karen Margarida Pinto Freilão',
            'email' => 'kfreilão@gestroilenergy.com',
            'password' => Hash::make('kfreilão2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Ricardo Costa Marques',
            'email' => 'rcosta@gestroilenergy.com',
            'password' => Hash::make('rcosta2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Elisabete Tânia Roque Augusto',
            'email' => 'eaugusto@gestroilenergy.com',
            'password' => Hash::make('eaugusto2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Lorrana Do Nascimento Furtado',
            'email' => 'lfurtado@gestroilenergy.com',
            'password' => Hash::make('lfurtado2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Ana Rita Rodrigues Fernandes de Carvalho',
            'email' => 'acarvalho@gestroilenergy.com',
            'password' => Hash::make('acarvalho2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Carlos Manuel dos Santos de Sousa',
            'email' => 'csousa@gestroilenergy.com',
            'password' => Hash::make('csousa2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Gerson Aparecido da Silva',
            'email' => 'gsilva@gestroilenergy.com',
            'password' => Hash::make('gsilva2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Maria de Fátima Ferreira',
            'email' => 'mfatima@gestroilenergy.com',
            'password' => Hash::make('mfatima2025'),
            'is_admin' => false,
            'email_verified_at' => now()
        ]);
    }
}
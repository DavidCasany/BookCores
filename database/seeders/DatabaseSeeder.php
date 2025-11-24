<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. USUARIS
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@bookcores.test',
            'password' => Hash::make('password'),
        ]);

        $clientId = User::factory()->create([
            'name' => 'Client Habitual',
            'email' => 'client@bookcores.test',
            'password' => Hash::make('password'),
        ])->id;

        // 2. EDITORIALS
        DB::table('editorials')->insert([
            ['nom' => 'Editorial Planeta', 'descripcio' => 'Grup editorial líder.'],
            ['nom' => 'O’Reilly Media', 'descripcio' => 'Llibres tècnics i d\'informàtica.'],
        ]);

        // 3. AUTORS
        DB::table('autors')->insert([
            ['nom' => 'Patrick Rothfuss', 'biografia' => 'Autor de fantasia èpica.', 'user_id' => null],
            ['nom' => 'Taylor Otwell', 'biografia' => 'Creador de Laravel.', 'user_id' => 1],
        ]);

        // 4. LLIBRES (Ara inclou nota_promig i ids correctes)
        DB::table('llibres')->insert([
            [
                'id_llibre' => 1,
                'titol' => 'El Nom del Vent',
                'descripcio' => 'Kvothe explica la seva història.',
                'preu' => 20.50,
                'nota_promig' => 4.9, // Ja no donarà error
                'img_portada' => 'vent.jpg',
                'fitxer_pdf' => 'vent.pdf',
                'autor_id' => 1, 
                'editorial_id' => 1,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'id_llibre' => 2,
                'titol' => 'Laravel Up & Running',
                'descripcio' => 'Guia completa del framework.',
                'preu' => 45.00,
                'nota_promig' => 4.5,
                'img_portada' => 'laravel.jpg',
                'fitxer_pdf' => 'laravel.pdf',
                'autor_id' => 2,
                'editorial_id' => 2,
                'created_at' => now(), 'updated_at' => now(),
            ]
        ]);

        // 5. RESSENYES
        DB::table('ressenyes')->insert([
            [
                'text' => 'Una obra mestra de la fantasia.',
                'puntuacio' => 5,
                'user_id' => $clientId,
                'llibre_id' => 1,
                'created_at' => now(), 'updated_at' => now(),
            ]
        ]);

        // 6. COMPRA (HEADER)
        $idCompra = DB::table('compres')->insertGetId([
            'id_compra' => 1,
            'total' => 65.50,
            'user_id' => $clientId,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // 7. COMPRA DETALLS (PIVOT)
        DB::table('compra_llibre')->insert([
            [
                'compra_id' => $idCompra,
                'llibre_id' => 1,
                'quantitat' => 1,
                'preu_unitari' => 20.50,
            ],
            [
                'compra_id' => $idCompra,
                'llibre_id' => 2,
                'quantitat' => 1,
                'preu_unitari' => 45.00,
            ]
        ]);
    }
}
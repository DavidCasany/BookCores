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
            ['id' => 1, 'nom' => 'Edicions Màgiques', 'descripcio' => 'Especialistes en fantasia.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nom' => 'Llibres del Futur', 'descripcio' => 'Ciència-ficció i misteri.', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 3. AUTORS (MODIFICAT: Canvi de nom de l'autora 1)
        DB::table('autors')->insert([
            ['id' => 1, 'nom' => 'Sandra Martínez Romero', 'biografia' => 'Escriptora apassionada pels dracs i les llegendes.', 'user_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nom' => 'Anaïs F. Gómez', 'biografia' => 'Autora especialitzada en misteris de la natura.', 'user_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nom' => 'Laura P. Martínez', 'biografia' => 'Navegant i escriptora d\'aventures estel·lars.', 'user_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 4. LLIBRES
        DB::table('llibres')->insert([
            [
                'id_llibre' => 1,
                'titol' => 'L\'El Ressò de les Cendres',
                'genere' => 'Fantasia',
                'descripcio' => 'Una història èpica sobre el despertar d\'una espècie extinta.',
                'preu' => 19.95,
                'nota_promig' => 5.0,
                'img_portada' => 'drag_negre.jpg',
                'img_hero' => 'h_drag_negre.jpg',
                'fitxer_pdf' => 'placeholder.pdf',
                'autor_id' => 1, // Sandra Martínez Romero
                'editorial_id' => 1,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'id_llibre' => 2,
                'titol' => 'L\'Ombra del Bosc',
                'genere' => 'Misteri',
                'descripcio' => 'Què s\'amaga entre els arbres quan cau la nit? Un thriller verd.',
                'preu' => 18.50,
                'nota_promig' => 4.5,
                'img_portada' => 'bosc_verd.jpg',
                'img_hero' => 'h_bosc_verd.jpg',
                'fitxer_pdf' => 'placeholder.pdf',
                'autor_id' => 2, // Anaïs F. Gómez
                'editorial_id' => 1,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'id_llibre' => 3,
                'titol' => 'Navegant Entre Estrelles',
                'genere' => 'Ciència-Ficció',
                'descripcio' => 'El viatge del vaixell blau més enllà de la galàxia coneguda.',
                'preu' => 22.00,
                'nota_promig' => 4.8,
                'img_portada' => 'vaixell_blau.jpg',
                'img_hero' => 'h_vaixell_blau.jpg',
                'fitxer_pdf' => 'placeholder.pdf',
                'autor_id' => 3, // Laura P. Martínez
                'editorial_id' => 2,
                'created_at' => now(), 'updated_at' => now(),
            ]
        ]);

        // 5. RESSENYES
        DB::table('ressenyes')->insert([
            [
                'text' => 'El drac violeta m\'ha robat el cor!',
                'puntuacio' => 5,
                'user_id' => $clientId,
                'llibre_id' => 1,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'text' => 'Un viatge increïble per l\'espai.',
                'puntuacio' => 5,
                'user_id' => $clientId,
                'llibre_id' => 3,
                'created_at' => now(), 'updated_at' => now(),
            ]
        ]);

        // 6. COMPRA
        $idCompra = DB::table('compres')->insertGetId([
            'id_compra' => 1,
            'total' => 41.95,
            'user_id' => $clientId,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // 7. DETALLS COMPRA
        DB::table('compra_llibre')->insert([
            [
                'compra_id' => $idCompra,
                'llibre_id' => 1,
                'quantitat' => 1,
                'preu_unitari' => 19.95,
            ],
            [
                'compra_id' => $idCompra,
                'llibre_id' => 3,
                'quantitat' => 1,
                'preu_unitari' => 22.00,
            ]
        ]);
    }
}
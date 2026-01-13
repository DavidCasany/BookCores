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

        // 3. AUTORS
        DB::table('autors')->insert([
            ['id' => 1, 'nom' => 'Sandra Martínez Romero', 'biografia' => 'Escriptora apassionada pels dracs.', 'user_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nom' => 'Anaïs F. Gómez', 'biografia' => 'Autora especialitzada en misteris.', 'user_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nom' => 'Laura P. Martínez', 'biografia' => 'Navegant i escriptora.', 'user_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 4. LLIBRES (Amb els noms de fitxer REALS)
        DB::table('llibres')->insert([
            [
                'id_llibre' => 1,
                'titol' => 'El Ressò de les Cendres',
                'genere' => 'Fantasia',
                'descripcio' => 'Una història èpica...',
                'preu' => 19.95,
                'nota_promig' => 5.0,
                'img_portada' => 'drag_negre.jpg',      // CORREGIT
                'img_hero' => 'h_drag_negre.png',       // CORREGIT (.png i prefix h_)
                'fitxer_pdf' => 'placeholder.pdf',
                'autor_id' => 1, 
                'editorial_id' => 1,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'id_llibre' => 2,
                'titol' => 'L\'Ombra del Bosc',
                'genere' => 'Misteri',
                'descripcio' => 'Thriller verd...',
                'preu' => 18.50,
                'nota_promig' => 4.5,
                'img_portada' => 'bosc_verd.jpg',
                'img_hero' => 'h_bosc_verd.png',        // CORREGIT (.png i prefix h_)
                'fitxer_pdf' => 'placeholder.pdf',
                'autor_id' => 2, 
                'editorial_id' => 1,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'id_llibre' => 3,
                'titol' => 'Navegant entre Estrelles',
                'genere' => 'Ciència-Ficció',
                'descripcio' => 'Viatge espacial...',
                'preu' => 22.00,
                'nota_promig' => 4.8,
                'img_portada' => 'vaixell_blau.jpg',
                'img_hero' => 'h_vaixell_blau.png',     // CORREGIT (.png i prefix h_)
                'fitxer_pdf' => 'placeholder.pdf',
                'autor_id' => 3, 
                'editorial_id' => 2,
                'created_at' => now(), 'updated_at' => now(),
            ]
        ]);

        // 5. RESSENYES
        DB::table('ressenyes')->insert([
            ['text' => 'Genial!', 'puntuacio' => 5, 'user_id' => $clientId, 'llibre_id' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 6. COMPRES
        $idCompra = DB::table('compres')->insertGetId([
            'id_compra' => 1, 'total' => 41.95, 'user_id' => $clientId, 'created_at' => now(), 'updated_at' => now(),
        ]);
        
        DB::table('compra_llibre')->insert([
            ['compra_id' => $idCompra, 'llibre_id' => 1, 'quantitat' => 1, 'preu_unitari' => 19.95],
            ['compra_id' => $idCompra, 'llibre_id' => 3, 'quantitat' => 1, 'preu_unitari' => 22.00],
        ]);
    }
}
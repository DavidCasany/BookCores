<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. CREAR USUARIS
        DB::table('users')->insert([
            'name' => 'Admin Proves',
            'email' => 'admin@bookcores.com',
            'password' => Hash::make('password'), 
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Un altre usuari normal
        DB::table('users')->insert([
            'name' => 'Laia Lectora',
            'email' => 'laia@example.com',
            'password' => Hash::make('12345678'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. CREAR EDITORIALS
        DB::table('editorials')->insert([
            [
                'nom' => 'Editorial Empúries',
                'descripcio' => 'Especialitzats en literatura catalana.',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'nom' => 'Penguin Random House',
                'descripcio' => 'Grup editorial internacional.',
                'created_at' => now(), 'updated_at' => now()
            ]
        ]);

        // 3. CREAR AUTORS
        // Autor 1: J.K. Rowling (No vinculat a cap usuari, és un autor famós)
        DB::table('autors')->insert([
            'nom' => 'J.K. Rowling',
            'biografia' => 'Autora britànica coneguda per la saga Harry Potter.',
            'user_id' => null, 
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Autor 2: Un autor local que també és usuari (vinculat a l'usuari 2, la Laia)
        // Suposem que la Laia ha escrit un llibre
        DB::table('autors')->insert([
            'nom' => 'Laia Lectora',
            'biografia' => 'Escriptora novella apassionada pel misteri.',
            'user_id' => 2, // ID de la Laia
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. CREAR LLIBRES
        // Llibre 1 (Harry Potter)
        DB::table('llibres')->insert([
            'titol' => 'Harry Potter i la Pedra Filosofal',
            'descripcio' => 'El nen que va sobreviure comença la seva màgia.',
            'preu' => 20.50,
            'img_portada' => 'https://m.media-amazon.com/images/I/81q77Q39nEL._AC_UF1000,1000_QL80_.jpg', // URL de prova
            'fitxer_pdf' => 'llibres/harry_potter_demo.pdf', // Ruta simulada
            'autor_id' => 1, // J.K. Rowling
            'editorial_id' => 2, // Penguin
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Llibre 2 (Llibre de la Laia)
        DB::table('llibres')->insert([
            'titol' => 'Misteri a Vic',
            'descripcio' => 'Una història de suspens a la plaça major.',
            'preu' => 15.00,
            'img_portada' => null, // Sense foto
            'fitxer_pdf' => 'llibres/misteri_vic.pdf',
            'autor_id' => 2, // Laia
            'editorial_id' => 1, // Empúries
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 5. CREAR COMPRES
        // L'usuari 1 (Admin) ha comprat el llibre 1
        DB::table('compres')->insert([
            'user_id' => 1,
            'llibre_id' => 1,
            'preu_pagat' => 20.50,
            'created_at' => now(), // Comprat ara mateix
            'updated_at' => now(),
        ]);

        // 6. CREAR RESENYES
        // L'usuari 1 opina sobre el llibre 1
        DB::table('resenyes')->insert([
            'text' => 'M\'ha encantat, un clàssic indispensable.',
            'puntuacio' => 5,
            'user_id' => 1,
            'llibre_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
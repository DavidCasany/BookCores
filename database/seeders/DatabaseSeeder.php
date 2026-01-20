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
        // usuaris
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@bookcores.com',
            'password' => 'Admin123456',
        ]);

        $clientId = User::factory()->create([
            'name' => 'Client Habitual',
            'email' => 'client@bookcores.test',
            'password' => Hash::make('password'),
        ])->id;

        // editorials
        DB::table('editorials')->insert([
            ['id' => 1, 'nom' => 'Autopublicat', 'descripcio' => 'No pertany a cap editorial.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nom' => 'Edicions Màgiques', 'descripcio' => 'Especialistes en fantasia.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nom' => 'Llibres del Futur', 'descripcio' => 'Ciència-ficció i misteri.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nom' => 'Editorial Històrica', 'descripcio' => 'Novel·la històrica i clàssics.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'nom' => 'TechBooks', 'descripcio' => 'Manuals tècnics i informàtica.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'nom' => 'Cor de Tinta', 'descripcio' => 'Novel·la romàntica i drama.', 'created_at' => now(), 'updated_at' => now()]
        ]);

        // autors
        
        DB::table('autors')->insert([
            ['id' => 0, 'nom' => 'Anònim', 'biografia' => 'Autor genèric del sistema.', 'user_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 1, 'nom' => 'Sandra Martínez Romero', 'biografia' => 'Escriptora apassionada pels dracs.', 'user_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nom' => 'Anaïs F. Gómez', 'biografia' => 'Autora especialitzada en misteris.', 'user_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nom' => 'Laura P. Martínez', 'biografia' => 'Navegant i escriptora.', 'user_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nom' => 'Marc Vilar', 'biografia' => 'Historiador i novel·lista.', 'user_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'nom' => 'Elena Rossell', 'biografia' => 'Experta en tecnologia i IA.', 'user_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'nom' => 'Jordi Pi', 'biografia' => 'Escriptor de thrillers psicològics.', 'user_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'nom' => 'Sofia Deulofeu', 'biografia' => 'Poeta i somiadora.', 'user_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'nom' => 'Kevin Smith', 'biografia' => 'Autor internacional de Best Sellers.', 'user_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // llibres

        $llibres = [
            [
                'id_llibre' => 1,
                'titol' => 'El Ressò de les Cendres',
                'genere' => 'Fantasia',
                'descripcio' => 'Una història èpica on les cendres dels dracs antics amaguen un secret que podria canviar el món per sempre.',
                'preu' => 19.95,
                'nota_promig' => 5.0,
                'img_portada' => 'drag_negre.jpg',
                'img_hero' => 'h_drag_negre.png',
                'fitxer_pdf' => 'El_resso_de_les_cendres.pdf',
                'autor_id' => 1,
                'editorial_id' => 2,
            ],
            [
                'id_llibre' => 2,
                'titol' => 'L\'Ombra del Bosc',
                'genere' => 'Misteri',
                'descripcio' => 'Un thriller verd que t\'atraparà des de la primera pàgina. Què s\'amaga darrere dels arbres mil·lenaris?',
                'preu' => 18.50,
                'nota_promig' => 4.5,
                'img_portada' => 'bosc_verd.jpg',
                'img_hero' => 'h_bosc_verd.png',
                'fitxer_pdf' => 'L_ombra_del_bosc_demo.pdf',
                'autor_id' => 2,
                'editorial_id' => 2,
            ],
            [
                'id_llibre' => 3,
                'titol' => 'Navegant entre Estrelles',
                'genere' => 'Ciència-Ficció',
                'descripcio' => 'Un viatge espacial sense retorn. La humanitat busca una nova llar, però l\'univers és més hostil del que pensaven.',
                'preu' => 22.00,
                'nota_promig' => 4.8,
                'img_portada' => 'vaixell_blau.jpg',
                'img_hero' => 'h_vaixell_blau.png',
                'fitxer_pdf' => 'Navegant_entre_estrelles.pdf',
                'autor_id' => 3,
                'editorial_id' => 3,
            ],

            [
                'id_llibre' => 4,
                'titol' => 'La Ciutat de Vidre',
                'genere' => 'Fantasia',
                'descripcio' => 'Una ciutat invisible als ulls dels humans.',
                'preu' => 15.90,
                'nota_promig' => 4.2,
                'img_portada' => 'la_ciutat_de_vidre.jpg',
                'img_hero' => 'h_la_ciutat_de_vidre.png',
                'fitxer_pdf' => 'La_ciutat_de_vidre_demo.pdf',
                'autor_id' => 1,
                'editorial_id' => 2,
            ],
            [
                'id_llibre' => 5,
                'titol' => 'Codi Infinit',
                'genere' => 'Informàtica',
                'descripcio' => 'Apreneu els secrets dels algorismes moderns.',
                'preu' => 29.99,
                'nota_promig' => 4.7,
                'img_portada' => 'codi_infinit.jpg',
                'img_hero' => 'h_codi_infinit.png',
                'fitxer_pdf' => 'placeholder.pdf',
                'autor_id' => 5,
                'editorial_id' => 5,
            ],
            ['id_llibre' => 6, 'titol' => 'Amor a la Toscana', 'genere' => 'Romàntica', 'descripcio' => 'Un estiu que canviarà la vida de la protagonista.', 'preu' => 12.50, 'nota_promig' => 3.9, 'img_portada' => 'amor_a_la_toscana.jpg', 'img_hero' => 'h_amor_a_la_toscana.png', 'fitxer_pdf' => 'placeholder.pdf', 'autor_id' => 7, 'editorial_id' => 6],
            ['id_llibre' => 7, 'titol' => 'L\'Enigma del Far', 'genere' => 'Misteri', 'descripcio' => 'Ningú sap qui va encendre el far aquella nit.', 'preu' => 17.00, 'nota_promig' => 4.4, 'img_portada' => 'energia_del_far.jpg', 'img_hero' => 'h_energia_del_far.png', 'fitxer_pdf' => 'placeholder.pdf', 'autor_id' => 2, 'editorial_id' => 3],
            ['id_llibre' => 8, 'titol' => 'Revolució Digital', 'genere' => 'Tecnologia', 'descripcio' => 'Com la IA està transformant la societat.', 'preu' => 21.00, 'nota_promig' => 4.9, 'img_portada' => 'revolucio_digital.jpg', 'img_hero' => 'h_revolucio_digital.png', 'fitxer_pdf' => 'placeholder.pdf', 'autor_id' => 5, 'editorial_id' => 5],
            ['id_llibre' => 9, 'titol' => 'El Secret dels Templers', 'genere' => 'Històrica', 'descripcio' => 'Una aventura a través de l\'edat mitjana.', 'preu' => 24.50, 'nota_promig' => 4.1, 'img_portada' => 'el_secret_dels_templers.jpg', 'img_hero' => 'h_el_secret_dels_templers.png', 'fitxer_pdf' => 'placeholder.pdf', 'autor_id' => 4, 'editorial_id' => 4],
            ['id_llibre' => 10, 'titol' => 'Cuina per a Dummies', 'genere' => 'Cuina', 'descripcio' => 'Receptes fàcils per a tothom.', 'preu' => 19.90, 'nota_promig' => 3.5, 'img_portada' => 'cuina_per_dummies.jpg', 'img_hero' => 'h_cuina_per_dummies.png', 'fitxer_pdf' => 'placeholder.pdf', 'autor_id' => 8, 'editorial_id' => 6],
            ['id_llibre' => 11, 'titol' => 'Viatge al Centre de la Ment', 'genere' => 'Psicologia', 'descripcio' => 'Entenent com pensem i sentim.', 'preu' => 16.80, 'nota_promig' => 4.3, 'img_portada' => 'viatge_centre_ment.jpg', 'img_hero' => 'h_viatge_centre_ment.png', 'fitxer_pdf' => 'placeholder.pdf', 'autor_id' => 6, 'editorial_id' => 5],
            ['id_llibre' => 12, 'titol' => 'Les Ombres de Barcelona', 'genere' => 'Thriller', 'descripcio' => 'Un assassí en sèrie camina per les Rambles.', 'preu' => 18.90, 'nota_promig' => 4.6, 'img_portada' => 'ombres_bcn.jpg', 'img_hero' => 'h_ombres_bcn.png', 'fitxer_pdf' => 'placeholder.pdf', 'autor_id' => 6, 'editorial_id' => 3],
            ['id_llibre' => 13, 'titol' => 'Dracs i Masmorres: Guia', 'genere' => 'Fantasia, Rol', 'descripcio' => 'El manual definitiu per a mestres del calabós.', 'preu' => 35.00, 'nota_promig' => 5.0, 'img_portada' => 'dnd_guia.jpg', 'img_hero' => 'h_dnd_guia.png', 'fitxer_pdf' => 'placeholder.pdf', 'autor_id' => 1, 'editorial_id' => 2],
            ['id_llibre' => 14, 'titol' => 'PHP per a Experts', 'genere' => 'Informàtica', 'descripcio' => 'Domina Laravel i el desenvolupament web.', 'preu' => 40.00, 'nota_promig' => 4.8, 'img_portada' => 'experts_php.jpg', 'img_hero' => 'h_experts_php.jpg', 'fitxer_pdf' => 'placeholder.pdf', 'autor_id' => 5, 'editorial_id' => 5],
            ['id_llibre' => 15, 'titol' => 'Poemes de Tardor', 'genere' => 'Poesia', 'descripcio' => 'Versos melancòlics per llegir amb cafè.', 'preu' => 10.00, 'nota_promig' => 4.0, 'img_portada' => 'poemes_de_tardor.jpg', 'img_hero' => 'h_poemes_de_tardor.jpg', 'fitxer_pdf' => 'placeholder.pdf', 'autor_id' => 7, 'editorial_id' => 6],
            ['id_llibre' => 16, 'titol' => 'La Guerra dels Xips', 'genere' => 'Ciència-Ficció', 'descripcio' => 'Quan els ordinadors prenen el control.', 'preu' => 20.50, 'nota_promig' => 4.2, 'img_portada' => 'guerra_xips.jpg', 'img_hero' => 'h_guerra_xips.jpg', 'fitxer_pdf' => 'placeholder.pdf', 'autor_id' => 8, 'editorial_id' => 3],
            ['id_llibre' => 17, 'titol' => 'Assassinat al Tren', 'genere' => 'Misteri', 'descripcio' => 'Un clàssic reinventat.', 'preu' => 14.20, 'nota_promig' => 4.5, 'img_portada' => 'assassinat_tren.jpg', 'img_hero' => 'h_assassinat_tren.jpg', 'fitxer_pdf' => 'placeholder.pdf', 'autor_id' => 2, 'editorial_id' => 4],
            ['id_llibre' => 18, 'titol' => 'La Història de Catalunya', 'genere' => 'Històrica', 'descripcio' => 'Des de Guifré el Pilós fins avui.', 'preu' => 25.00, 'nota_promig' => 4.7, 'img_portada' => 'historia_cat.jpg', 'img_hero' => 'h_historia_cat.jpg', 'fitxer_pdf' => 'placeholder.pdf', 'autor_id' => 4, 'editorial_id' => 4],
            ['id_llibre' => 19, 'titol' => 'Disseny Web Modern', 'genere' => 'Informàtica', 'descripcio' => 'Aprèn Tailwind i AlpineJS.', 'preu' => 28.50, 'nota_promig' => 4.6, 'img_portada' => 'web_modern.jpg', 'img_hero' => 'h_web_modern.jpg', 'fitxer_pdf' => 'placeholder.pdf', 'autor_id' => 5, 'editorial_id' => 5],
            ['id_llibre' => 20, 'titol' => 'El Jardí Oblidat', 'genere' => 'Fantasia', 'descripcio' => 'Flors que parlen i arbres que caminen.', 'preu' => 16.50, 'nota_promig' => 3.8, 'img_portada' => 'jardi_oblidat.jpg', 'img_hero' => 'h_jardi_oblidat.jpg', 'fitxer_pdf' => 'placeholder.pdf', 'autor_id' => 7, 'editorial_id' => 2],
            ['id_llibre' => 21, 'titol' => 'Intel·ligència Artificial', 'genere' => 'Ciència', 'descripcio' => 'Ètica i futur de la IA.', 'preu' => 22.90, 'nota_promig' => 4.9, 'img_portada' => 'ai.jpg', 'img_hero' => 'h_ai.jpg', 'fitxer_pdf' => 'placeholder.pdf', 'autor_id' => 5, 'editorial_id' => 5],
            ['id_llibre' => 22, 'titol' => 'Riu Avall', 'genere' => 'Aventures', 'descripcio' => 'Descens en caiac pel riu més perillós.', 'preu' => 13.50, 'nota_promig' => 4.0, 'img_portada' => 'riu_avall.jpg', 'img_hero' => 'h_riu_avall.jpg', 'fitxer_pdf' => 'placeholder.pdf', 'autor_id' => 3, 'editorial_id' => 4],
            ['id_llibre' => 23, 'titol' => 'El Darrer Alè', 'genere' => 'Terror', 'descripcio' => 'No podràs dormir després de llegir-lo.', 'preu' => 15.66, 'nota_promig' => 4.3, 'img_portada' => 'darrer_ale.jpg', 'img_hero' => 'h_darrer_ale.jpg', 'fitxer_pdf' => 'placeholder.pdf', 'autor_id' => 8, 'editorial_id' => 3],
        ];

        // inserir llibres
        foreach ($llibres as $llibre) {
            DB::table('llibres')->insert([
                'id_llibre' => $llibre['id_llibre'],
                'titol' => $llibre['titol'],
                'genere' => $llibre['genere'],
                'descripcio' => $llibre['descripcio'],
                'preu' => $llibre['preu'],
                'nota_promig' => $llibre['nota_promig'],
                'img_portada' => $llibre['img_portada'] ?? null,
                'img_hero' => $llibre['img_hero'] ?? null,
                'fitxer_pdf' => $llibre['fitxer_pdf'],
                'autor_id' => $llibre['autor_id'],
                'editorial_id' => $llibre['editorial_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ressenyes
        DB::table('ressenyes')->insert([
            ['text' => 'Genial!', 'puntuacio' => 5, 'user_id' => $clientId, 'llibre_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['text' => 'Molt interessant per aprendre.', 'puntuacio' => 4, 'user_id' => $clientId, 'llibre_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['text' => 'No m\'ha agradat el final.', 'puntuacio' => 2, 'user_id' => $clientId, 'llibre_id' => 6, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $ficcion = Category::firstOrCreate(['name' => 'Ficción']);
        $tecnologia = Category::firstOrCreate(['name' => 'Tecnología']);
        $historia = Category::firstOrCreate(['name' => 'Historia']);

        Book::firstOrCreate(
            ['title' => 'Cien años de soledad', 'author' => 'Gabriel García Márquez'],
            [
                'description' => 'Obra cumbre de la literatura hispanoamericana que narra la historia de la familia Buendía en el pueblo ficticio de Macondo.',
                'published_year' => 1967,
                'category_id' => $ficcion->id,
            ]
        );

        Book::firstOrCreate(
            ['title' => 'Clean Code: A Handbook of Agile Software Craftsmanship', 'author' => 'Robert C. Martin'],
            [
                'description' => 'Guía fundamental sobre buenas prácticas, refactorización y principios de diseño para el desarrollo de software profesional.',
                'published_year' => 2008,
                'category_id' => $tecnologia->id,
            ]
        );

        Book::firstOrCreate(
            ['title' => 'Sapiens: De animales a dioses', 'author' => 'Yuval Noah Harari'],
            [
                'description' => 'Un recorrido histórico y antropológico sobre la evolución de la humanidad desde los primeros homínidos hasta la era moderna.',
                'published_year' => 2011,
                'category_id' => $historia->id,
            ]
        );
    }
}

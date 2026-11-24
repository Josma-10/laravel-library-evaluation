<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_all_books_with_eager_loaded_category(): void
    {
        $category = Category::create(['name' => 'Ciencias']);
        Book::create([
            'title' => 'Física Universitaria',
            'author' => 'Sears y Zemansky',
            'description' => 'Texto clásico de mecánica y termodinámica.',
            'published_year' => 2018,
            'category_id' => $category->id,
        ]);

        $response = $this->getJson('/api/books');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment([
                'title' => 'Física Universitaria',
                'author' => 'Sears y Zemansky',
            ])
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'title',
                    'author',
                    'description',
                    'published_year',
                    'category_id',
                    'category' => [
                        'id',
                        'name',
                    ],
                ],
            ]);
    }

    public function test_can_filter_books_by_category(): void
    {
        $category1 = Category::create(['name' => 'Derecho']);
        $category2 = Category::create(['name' => 'Economía']);

        $book1 = Book::create([
            'title' => 'Código Civil Comentado',
            'author' => 'Juristas Asociados',
            'published_year' => 2020,
            'category_id' => $category1->id,
        ]);

        $book2 = Book::create([
            'title' => 'Macroeconomía Básica',
            'author' => 'Gregory Mankiw',
            'published_year' => 2019,
            'category_id' => $category2->id,
        ]);

        $response = $this->getJson('/api/books?category=' . $category1->id);

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['title' => 'Código Civil Comentado'])
            ->assertJsonMissing(['title' => 'Macroeconomía Básica']);
    }

    public function test_can_create_book_with_valid_data(): void
    {
        $category = Category::create(['name' => 'Filosofía']);

        $payload = [
            'title' => 'Ética a Nicómaco',
            'author' => 'Aristóteles',
            'description' => 'Tratado fundamental sobre la virtud y la felicidad.',
            'published_year' => 2015,
            'category_id' => $category->id,
        ];

        $response = $this->postJson('/api/books', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'title' => 'Ética a Nicómaco',
                'author' => 'Aristóteles',
            ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Ética a Nicómaco',
            'author' => 'Aristóteles',
            'category_id' => $category->id,
        ]);
    }

    public function test_validates_required_fields_when_creating_book(): void
    {
        $response = $this->postJson('/api/books', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'author', 'published_year', 'category_id']);
    }

    public function test_can_update_existing_book(): void
    {
        $category = Category::create(['name' => 'Arte']);
        $book = Book::create([
            'title' => 'Historia del Arte',
            'author' => 'E. H. Gombrich',
            'published_year' => 1950,
            'category_id' => $category->id,
        ]);

        $updateData = [
            'title' => 'La Historia del Arte (Edición de Lujo)',
            'published_year' => 1995,
        ];

        $response = $this->putJson("/api/books/{$book->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'title' => 'La Historia del Arte (Edición de Lujo)',
                'published_year' => 1995,
            ]);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'La Historia del Arte (Edición de Lujo)',
            'published_year' => 1995,
        ]);
    }

    public function test_returns_404_when_updating_non_existent_book(): void
    {
        $category = Category::create(['name' => 'Música']);

        $response = $this->putJson('/api/books/9999', [
            'title' => 'Libro Inexistente',
            'author' => 'Autor Desconocido',
            'published_year' => 2021,
            'category_id' => $category->id,
        ]);

        $response->assertStatus(404);
    }

    public function test_can_delete_existing_book(): void
    {
        $category = Category::create(['name' => 'Sociología']);
        $book = Book::create([
            'title' => 'La Ética Protestante',
            'author' => 'Max Weber',
            'published_year' => 1905,
            'category_id' => $category->id,
        ]);

        $response = $this->deleteJson("/api/books/{$book->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    public function test_returns_404_when_deleting_non_existent_book(): void
    {
        $response = $this->deleteJson('/api/books/9999');

        $response->assertStatus(404);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_all_categories(): void
    {
        Category::create(['name' => 'Administración Pública']);
        Category::create(['name' => 'Ciencias Políticas']);

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonFragment(['name' => 'Administración Pública'])
            ->assertJsonFragment(['name' => 'Ciencias Políticas']);
    }

    public function test_can_create_category(): void
    {
        $payload = ['name' => 'Auditoría Fiscal'];

        $response = $this->postJson('/api/categories', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Auditoría Fiscal']);

        $this->assertDatabaseHas('categories', ['name' => 'Auditoría Fiscal']);
    }

    public function test_validates_unique_category_name(): void
    {
        Category::create(['name' => 'Derecho Constitucional']);

        $response = $this->postJson('/api/categories', [
            'name' => 'Derecho Constitucional',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_can_delete_category_without_associated_books(): void
    {
        $category = Category::create(['name' => 'Categoría Temporal']);

        $response = $this->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_returns_404_when_deleting_non_existent_category(): void
    {
        $response = $this->deleteJson('/api/categories/9999');

        $response->assertStatus(404);
    }

    public function test_blocks_deletion_of_category_with_associated_books(): void
    {
        $category = Category::create(['name' => 'Patrimonio Cultural']);

        Book::create([
            'title' => 'Inventario de Bienes Históricos',
            'author' => 'Comisión Nacional de Patrimonio',
            'published_year' => 2021,
            'category_id' => $category->id,
        ]);

        $response = $this->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(409)
            ->assertJsonFragment([
                'message' => 'No es posible eliminar la categoría porque tiene libros asociados',
            ]);

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }
}

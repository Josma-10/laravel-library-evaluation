<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    $categoryFilter = $request->query('category');
    $query = Book::with('category');

    if ($categoryFilter) {
        $query->where('category_id', $categoryFilter);
    }

    $books = $query->orderBy('id', 'desc')->get();
    $categories = Category::withCount('books')->orderBy('name')->get();

    return view('welcome', compact('books', 'categories', 'categoryFilter'));
});

// Rutas de compatibilidad directa
Route::get('/books', [BookController::class, 'index']);
Route::post('/books', [BookController::class, 'store']);
Route::get('/books/{id}', [BookController::class, 'show']);
Route::put('/books/{id}', [BookController::class, 'update']);
Route::delete('/books/{id}', [BookController::class, 'destroy']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

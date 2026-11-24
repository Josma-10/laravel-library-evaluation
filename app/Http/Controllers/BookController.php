<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Book::with('category');

        if ($request->filled('category')) {
            $query->where('category_id', $request->query('category'));
        }

        return response()->json($query->get(), 200);
    }

    public function store(StoreBookRequest $request): JsonResponse
    {
        $book = Book::create($request->validated());

        return response()->json($book->load('category'), 201);
    }

    public function show(string|int $id): JsonResponse
    {
        $book = Book::with('category')->find($id);

        if (!$book) {
            return response()->json([
                'message' => 'Libro no encontrado',
            ], 404);
        }

        return response()->json($book, 200);
    }

    public function update(UpdateBookRequest $request, string|int $id): JsonResponse
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'message' => 'Libro no encontrado',
            ], 404);
        }

        $book->update($request->validated());

        return response()->json($book->fresh()->load('category'), 200);
    }

    public function destroy(string|int $id): JsonResponse
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'message' => 'Libro no encontrado',
            ], 404);
        }

        $book->delete();

        return response()->json([
            'message' => 'Libro eliminado exitosamente',
        ], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::all();

        return response()->json($categories, 200);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = Category::create($request->validated());

        return response()->json($category, 201);
    }

    public function show(string|int $id): JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Categoría no encontrada',
            ], 404);
        }

        return response()->json($category, 200);
    }

    public function destroy(string|int $id): JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Categoría no encontrada',
            ], 404);
        }

        if ($category->books()->exists()) {
            return response()->json([
                'message' => 'No es posible eliminar la categoría porque tiene libros asociados',
            ], 409);
        }

        $category->delete();

        return response()->json([
            'message' => 'Categoría eliminada exitosamente',
        ], 200);
    }
}

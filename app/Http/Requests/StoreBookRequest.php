<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $currentYear = (int) date('Y');

        return [
            'title' => 'required|string|min:3|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'published_year' => "required|integer|digits:4|min:1000|max:{$currentYear}",
            'category_id' => 'required|integer|exists:categories,id',
        ];
    }
}

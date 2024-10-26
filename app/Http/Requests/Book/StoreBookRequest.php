<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'book_name' => ['required', 'string', 'max:255', 'unique:books,book_name'], // Unique book_name
            'author' => ['required', 'string', 'max:255'], // Author
            'book_cover' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:4096'],  // 4MB
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'book_name.required' => 'The book name is required.',
            'book_name.string' => 'The book name must be a string.',
            'book_name.max' => 'The book name must not be greater than 255 characters.',
            'book_name.unique' => 'The book name has already been taken.',
            'author.required' => 'The author is required.',
            'author.string' => 'The author must be a string.',
            'author.max' => 'The author must not be greater than 255 characters.',
            'author.unique' => 'The author has already been taken.',
            'book_cover.required' => 'The book cover is required.',
            'book_cover.image' => 'The book cover must be an image.',
            'book_cover.mimes' => 'The book cover must be a file of type: png, jpg, jpeg, gif.',
            'book_cover.max' => 'The book cover must not be greater than 4MB.',
        ];
    }
}

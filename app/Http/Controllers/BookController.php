<?php

namespace App\Http\Controllers;

use App\Http\Requests\Book\StoreBookRequest;
use App\Http\Requests\Book\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of the books.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Return the view with the books
        return view('book.index');
    }

    /**
     * Store a newly created book in storage.
     *
     * @param  StoreBookRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBookRequest $request)
    {
        try {
            // Store the book cover and get its path
            $path = $request->file('book_cover')->store('books', 'public');

            // Create a new book
            Book::query()->create([
                'book_name' => $request->book_name,
                'author' => $request->author,
                'book_cover' => $path,
            ]);

            // Return a JSON response with a success message
            return response()->json([
                'message' => 'Book created successfully.',
            ]);
        } catch (\Exception $e) {
            // Return a JSON response with an error message
            return response()->json([
                'message' => 'An error occurred while creating the book.',
            ], 500);
        }
    }

    /**
     * Display the specific book.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        // Return JSON response with the book
        return BookResource::make($book);
    }

    /**
     * Update the specific book in storage.
     *
     * @param  UpdateBookRequest $request
     * @param  \App\Models\Book $book
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        try {
            // Rollback the transaction if an error occurs
            DB::transaction(function () use ($request, $book) {
                // Update the book cover if a new one is uploaded
                if ($request->hasFile('book_cover')) {
                    // Delete the old book cover
                    Storage::disk('public')->delete($book->book_cover);

                    // Store the new book cover and get its path
                    $path = $request->file('book_cover')->store('books', 'public');

                    // Update the book cover
                    $book->update([
                        'book_cover' => $path,
                    ]);
                }

                // Update the book details
                $book->update([
                    'book_name' => $request->book_name,
                    'author' => $request->author,
                ]);
            });
        } catch (\Exception $e) {
            // Return a JSON response with an error message
            return response()->json([
                'message' => 'An error occurred while updating the book.',
            ], 500);
        }
    }

    /**
     * Remove the specified book from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        try {
            // Delete the book cover
            Storage::disk('public')->delete($book->book_cover);

            // Delete the book
            $book->delete();

            // Return a JSON response with a success message
            return response()->json([
                'message' => 'Book deleted successfully.',
            ]);
        } catch (\Exception $e) {
            // Return a JSON response with an error message
            return response()->json([
                'message' => 'An error occurred while deleting the book.',
            ], 500);
        }
    }
}

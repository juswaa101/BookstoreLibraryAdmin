<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class BookApiController extends Controller
{
    /**
     * Handle the incoming request for the Books DataTable.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // Fetch all books from the database
        $books = Book::query()
            ->latest()
            ->get([
                'id',
                'book_name',
                'author',
                'book_cover',
            ]);

        // Return a JSON response with the books
        return DataTables::of($books)
            ->addColumn('book_name', function ($book) {
                return $book->book_name;
            })
            ->addColumn('author', function ($book) {
                return $book->author;
            })
            ->addColumn('book_cover', function ($book) {
                // Check if the book cover exists and the path is valid
                if ($book->book_cover) {
                    return '<img src="' . Storage::url($book->book_cover) . '" alt="' . $book->book_name . '" class="img-thumbnail" width="200" height="200">';
                } else {
                    return '<img src="https://via.placeholder.com/200" alt="' . $book->book_name . '" class="img-thumbnail" width="200" height="200">';
                }
            })
            ->addColumn('action', function ($book) {
                // Return the action buttons with font-awesome icons with id
                return '<button class="btn btn-primary edit-book btn-sm" data-id="' . $book->id .  '" title="Edit Book"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-danger delete-book btn-sm" data-id="' . $book->id .  '" title="Delete Book"><i class="fas fa-trash"></i></button>
                        <button class="btn btn-secondary view-book btn-sm" data-id="' . $book->id .  '" title="View Book"><i class="fas fa-eye"></i></button>';
            })
            ->rawColumns(['book_cover', 'action'])
            ->make(true);
    }
}

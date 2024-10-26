<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CsvController extends Controller
{
    /**
     * Import CSV file with format (book_name, author)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function import(Request $request)
    {
        // Validate the request
        $request->validate([
            'csvFile' => ['required', 'file', 'mimes:csv,txt']
        ]);

        try {
            $file = $request->file('csvFile');
            $file_path = $file->getRealPath();
            $data = array_map('str_getcsv', file($file_path));

            // Remove the first two header rows
            array_shift($data); // Removes "Bookstore Library Admin" row

            // Insert data into the database
            DB::transaction(function () use ($data) {
                foreach ($data as $row) {
                    // Skip rows if they don't have valid data
                    if (!isset($row[1]) || !isset($row[2])) continue;

                    // Check if the data already exists
                    $book = Book::where('book_name', $row[1])
                        ->where('author', $row[2])
                        ->first();

                    // Skip if the data already exists
                    if (!$book) {
                        // Insert the data into the database
                        Book::create([
                            'book_name' => $row[1],
                            'author' => $row[2],
                            'book_cover' => '', // Assuming book_cover is required
                        ]);
                    }
                }
            });

            return response()->json([
                'message' => 'CSV file imported successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

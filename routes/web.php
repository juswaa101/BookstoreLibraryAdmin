<?php

use App\Http\Controllers\BookApiController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CsvController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Redirect to the Books Index Page
Route::get('/', function () {
    return to_route('books.index');
})->name('home');

// Books DataTable API
Route::get('api/v1/books', BookApiController::class)
    ->name('books.api');

// Import CSV File
Route::post('import', [CsvController::class, 'import'])
    ->name('books.import');

// Books Resource Controller API (GET, POST, PUT, DELETE)
Route::resource('books', BookController::class)
    ->except('create', 'edit');

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    // Define the protected fillable property for the Book Model
    protected $fillable = [
        'book_name',
        'author',
        'book_cover',
    ];
}

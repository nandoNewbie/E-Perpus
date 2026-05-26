<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    //
    protected $fillable = [
        'title', 'author', 'publisher', 'published_place', 
        'published_year', 'edition', 'stock', 'language', 
        'isbn', 'description', 'cover', 'category', 'ddc'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Factories\HasFactory;

class Borrowing extends Model
{
    // izin pengisian data
    protected $fillable = [
        'user_id',
        'book_id',
        'status',
        'borrow_date',
        'due_date',
    ];
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}

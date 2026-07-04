<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Borrowing extends Model
{
    use HasFactory;
    const STATUS_PENDING = 'Pending';
    const STATUS_DITERIMA = 'Diterima';
    const STATUS_DITOLAK = 'Ditolak';
    const STATUS_DIKEMBALIKAN = 'Dikembalikan';
    const STATUS_EXPIRED = 'Expired';

    const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_DITERIMA,
        self::STATUS_DITOLAK,
        self::STATUS_DIKEMBALIKAN,
        self::STATUS_EXPIRED,
    ];

    protected $fillable = [
        'user_id',
        'book_id',
        'status',            // Berisi: 'Pending', 'Diterima', 'Ditolak'
        'extension_status',   // Berisi: null, 'pending_extension', 'approved_extension'
        'borrow_date',
        'due_date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
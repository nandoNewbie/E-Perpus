<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrowing_id',
        'return_date',
        'late_days',
        'fine_amount',
        'fine_status',
        'pustakawan_name',
    ];

    // Relasi ke data peminjaman
    public function borrowing(): BelongsTo
    {
        return $this->belongsTo(Borrowing::class, 'borrowing_id');
    }

    // Relasi ke admin/pustakawan yang memproses
    public function pustakawan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pustakawan_id');
    }
}
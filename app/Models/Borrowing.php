<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Borrowing extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'date_borrowing',
        'date_return',
        'status',
        'penalty_charge',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public static function boot()
    {
        parent::boot();

        // Saat create → stok berkurang
        static::creating(function ($borrowing) {
            $book = Book::find($borrowing->book_id);

            if ($book && $book->stok > 0) {
                $book->stok -= 1;
                $book->save();
            } else {
                throw new \Exception("Book is not available (stok habis).");
            }
        });

        // Saat update → cek status
        static::updating(function ($borrowing) {
            $book = Book::find($borrowing->book_id);

            // Jika status berubah jadi "returned" → stok bertambah
            if ($borrowing->isDirty('status') && $borrowing->status === 'returned') {
                $book->stok += 1;
                $book->save();
            }

            // Jika status berubah jadi "late" → hitung penalty
            if ($borrowing->isDirty('status') && $borrowing->status === 'late') {
                $dueDate = Carbon::parse($borrowing->date_borrowing)->addDays(7); // contoh 7 hari
                $daysLate = Carbon::now()->diffInDays($dueDate, false);

                if ($daysLate > 0) {
                    $borrowing->penalty_charge = $daysLate * 1000; // contoh Rp1000 per hari
                }
            }
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title_book',
        'author',
        'publisher',
        'year_publish',
        'isbn',
        'category',
        'description',
        'cover_image',
        'stock',
        'status',
    ];

    public function getStatusAttribute($value)
    {
        if ($this->stok <= 0) {
            return 'no available';
        }
        return $value;
    }


    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}


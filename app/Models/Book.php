<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'judul_buku',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'isbn',
        'kategori',
        'deskripsi',
        'cover_image',
    'stok',
        'status',
    ];

    public function getStatusAttribute($value)
{
    if ($this->stok <= 0) {
        return 'tidak tersedia';
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


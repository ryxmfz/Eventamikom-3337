<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'user_id',      // 👈 Ditambahkan agar terhubung ke Penyelenggara (Organizer)
        'category_id',
        'title',
        'description',
        'date',
        'location',
        'price',
        'stock',
        'poster_path'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    // 1. Relasi ke Penyelenggara (User / Organizer)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 2. Relasi ke Kategori Event
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // 3. Relasi ke Ulasan / Review
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // 4. Helper untuk menghitung rata-rata rating event ini
    public function averageRating()
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }
}

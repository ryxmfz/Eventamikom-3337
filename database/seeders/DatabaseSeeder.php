<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User Admin (Opsional, buat login)
        User::create([
            'name' => 'Admin Amikom',
            'email' => 'admin@amikom.ac.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // 2. Buat 3 Kategori
        $workshop = Category::create(['name' => 'Workshop', 'slug' => 'workshop']);
        $esport = Category::create(['name' => 'E-Sport', 'slug' => 'e-sport']);
        $seminar = Category::create(['name' => 'Seminar', 'slug' => 'seminar']);

        // 3. Buat 6 Event Acak & Logis
        // Workshop
        Event::create([
            'category_id' => $workshop->id,
            'title' => 'UI/UX Masterclass: Figma Pro',
            'description' => 'Belajar desain interface tingkat lanjut dengan Figma.',
            'date' => '2026-05-10 09:00:00',
            'location' => 'Lab ICT Amikom',
            'price' => 50000,
            'stock' => 30,
            'poster_path' => 'posters/uiux.png',
        ]);

        Event::create([
            'category_id' => $workshop->id,
            'title' => 'Laravel Backend Pro',
            'description' => 'Membangun API yang kuat dengan Laravel 11.',
            'date' => '2026-05-15 13:00:00',
            'location' => 'Inkubator Amikom',
            'price' => 75000,
            'stock' => 25,
            'poster_path' => 'posters/laravel.png',
        ]);

        // E-Sport
        Event::create([
            'category_id' => $esport->id,
            'title' => 'E-Sport U-Champ: Mobile Legends',
            'description' => 'Turnamen bergengsi antar mahasiswa Amikom.',
            'date' => '2026-06-01 10:00:00',
            'location' => 'Aula Gedung 4',
            'price' => 20000,
            'stock' => 16,
            'poster_path' => 'posters/ml.png',
        ]);

        Event::create([
            'category_id' => $esport->id,
            'title' => 'Valorant University League',
            'description' => 'Siapkan aim terbaikmu di liga kampus tahun ini.',
            'date' => '2026-06-05 09:00:00',
            'location' => 'Basement Gedung 4',
            'price' => 25000,
            'stock' => 8,
            'poster_path' => 'posters/valorant.png',
        ]);

        // Seminar
        Event::create([
            'category_id' => $seminar->id,
            'title' => 'Future of AI in Industry',
            'description' => 'Membahas dampak AI di dunia kerja tahun 2026.',
            'date' => '2026-04-30 09:00:00',
            'location' => 'Cinema Amikom',
            'price' => 0,
            'stock' => 150,
            'poster_path' => 'posters/ai.png',
        ]);

        Event::create([
            'category_id' => $seminar->id,
            'title' => 'Digital Marketing 101',
            'description' => 'Cara jualan produk digital lewat media sosial.',
            'date' => '2026-05-02 14:00:00',
            'location' => 'Zoom Meeting',
            'price' => 0,
            'stock' => 500,
            'poster_path' => 'posters/marketing.png',
        ]);
    }
}

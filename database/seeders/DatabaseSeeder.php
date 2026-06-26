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
        // 1. Buat User Admin (Menggunakan updateOrCreate agar terhindar dari error duplikat email)
        User::updateOrCreate(
            ['email' => 'admin@amikom.ac.id'], // Kolom yang dicek
            [
                'name' => 'Admin Amikom',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        // 2. Buat 3 Kategori (Cek berdasarkan slug)
        $workshop = Category::updateOrCreate(
            ['slug' => 'workshop'],
            ['name' => 'Workshop']
        );
        $esport = Category::updateOrCreate(
            ['slug' => 'e-sport'],
            ['name' => 'E-Sport']
        );
        $seminar = Category::updateOrCreate(
            ['slug' => 'seminar'],
            ['name' => 'Seminar']
        );

        // 3. Buat 6 Event Acak & Logis (Cek berdasarkan judul/title event)
        // Workshop
        Event::updateOrCreate(
            ['title' => 'UI/UX Masterclass: Figma Pro'],
            [
                'category_id' => $workshop->id,
                'description' => 'Belajar desain interface tingkat lanjut dengan Figma.',
                'date' => '2026-05-10 09:00:00',
                'location' => 'Lab ICT Amikom',
                'price' => 50000,
                'stock' => 30,
                'poster_path' => 'posters/uiux.png',
            ]
        );

        Event::updateOrCreate(
            ['title' => 'Laravel Backend Pro'],
            [
                'category_id' => $workshop->id,
                'description' => 'Membangun API yang kuat dengan Laravel 11.',
                'date' => '2026-05-15 13:00:00',
                'location' => 'Inkubator Amikom',
                'price' => 75000,
                'stock' => 25,
                'poster_path' => 'posters/laravel.png',
            ]
        );

        // E-Sport
        Event::updateOrCreate(
            ['title' => 'E-Sport U-Champ: Mobile Legends'],
            [
                'category_id' => $esport->id,
                'description' => 'Turnamen bergengsi antar mahasiswa Amikom.',
                'date' => '2026-06-01 10:00:00',
                'location' => 'Aula Gedung 4',
                'price' => 20000,
                'stock' => 16,
                'poster_path' => 'posters/ml.png',
            ]
        );

        Event::updateOrCreate(
            ['title' => 'Valorant University League'],
            [
                'category_id' => $esport->id,
                'description' => 'Siapkan aim terbaikmu di liga kampus tahun ini.',
                'date' => '2026-06-05 09:00:00',
                'location' => 'Basement Gedung 4',
                'price' => 25000,
                'stock' => 8,
                'poster_path' => 'posters/valorant.png',
            ]
        );

        // Seminar
        Event::updateOrCreate(
            ['title' => 'Future of AI in Industry'],
            [
                'category_id' => $seminar->id,
                'description' => 'Membahas dampak AI di dunia kerja tahun 2026.',
                'date' => '2026-04-30 09:00:00',
                'location' => 'Cinema Amikom',
                'price' => 0,
                'stock' => 150,
                'poster_path' => 'posters/ai.png',
            ]
        );

        Event::updateOrCreate(
            ['title' => 'Digital Marketing 101'],
            [
                'category_id' => $seminar->id,
                'description' => 'Cara jualan produk digital lewat media sosial.',
                'date' => '2026-05-02 14:00:00',
                'location' => 'Zoom Meeting',
                'price' => 0,
                'stock' => 500,
                'poster_path' => 'posters/marketing.png',
            ]
        );
    }
}

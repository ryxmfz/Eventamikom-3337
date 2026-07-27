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
        // 1. Akun SUPERADMIN Utama (Amikom Event Hub)
        $admin = User::updateOrCreate(
            ['email' => 'admin@amikom.ac.id'],
            [
                'name'              => 'AMIKOM EVENT HUB',
                'organization_name' => 'AMIKOM EVENT HUB',
                'password'          => bcrypt('password'), // Password: password
                'is_admin'          => 1,
                'role'              => 'admin',
                'organizer_status'  => 'approved',
            ]
        );

        // 2. Akun ORGANIZER 1: HIMA Sistem Informasi (Status: APPROVED - Untuk Tes Dashboard Terisolasi)
        $himasi = User::updateOrCreate(
            ['email' => 'himasi@amikom.ac.id'],
            [
                'name'              => 'HIMA SI Amikom',
                'organization_name' => 'HIMA Sistem Informasi',
                'password'          => bcrypt('password'), // Password: password
                'is_admin'          => 1,
                'role'              => 'organizer',
                'organizer_status'  => 'approved',
            ]
        );

        // 3. Akun ORGANIZER 2: HMIF (Status: PENDING - Untuk Tes Persetujuan Superadmin)
        $hmif = User::updateOrCreate(
            ['email' => 'hmif@amikom.ac.id'],
            [
                'name'              => 'HMIF Amikom',
                'organization_name' => 'HIMA Informatika',
                'password'          => bcrypt('password'), // Password: password
                'is_admin'          => 0,
                'role'              => 'organizer',
                'organizer_status'  => 'pending',
            ]
        );

        // 4. Akun PEMBELI / BUYER Demo (Manual Login Tanpa Google SSO)
        User::updateOrCreate(
            ['email' => 'pembeli@gmail.com'],
            [
                'name'              => 'Peserta Demo',
                'password'          => bcrypt('password'), // Password: password
                'is_admin'          => 0,
                'role'              => 'buyer',
                'organizer_status'  => 'approved',
            ]
        );

        // 5. Buat 3 Kategori
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

        // 6. Buat 6 Event & kaitkan ke Penyelenggara (user_id)
        // Workshop (Milik HIMA SI)
        Event::updateOrCreate(
            ['title' => 'UI/UX Masterclass: Figma Pro'],
            [
                'user_id'     => $himasi->id,
                'category_id' => $workshop->id,
                'description' => 'Belajar desain interface tingkat lanjut dengan Figma.',
                'date'        => '2026-05-10 09:00:00',
                'location'    => 'Lab ICT Amikom',
                'price'       => 50000,
                'stock'       => 30,
                'poster_path' => 'posters/uiux.png',
            ]
        );

        Event::updateOrCreate(
            ['title' => 'Laravel Backend Pro'],
            [
                'user_id'     => $himasi->id,
                'category_id' => $workshop->id,
                'description' => 'Membangun API yang kuat dengan Laravel 11.',
                'date'        => '2026-05-15 13:00:00',
                'location'    => 'Inkubator Amikom',
                'price'       => 75000,
                'stock'       => 25,
                'poster_path' => 'posters/laravel.png',
            ]
        );

        // E-Sport (Milik AMIKOM EVENT HUB)
        Event::updateOrCreate(
            ['title' => 'E-Sport U-Champ: Mobile Legends'],
            [
                'user_id'     => $admin->id,
                'category_id' => $esport->id,
                'description' => 'Turnamen bergengsi antar mahasiswa Amikom.',
                'date'        => '2026-06-01 10:00:00',
                'location'    => 'Aula Gedung 4',
                'price'       => 20000,
                'stock'       => 16,
                'poster_path' => 'posters/ml.png',
            ]
        );

        Event::updateOrCreate(
            ['title' => 'Valorant University League'],
            [
                'user_id'     => $admin->id,
                'category_id' => $esport->id,
                'description' => 'Siapkan aim terbaikmu di liga kampus tahun ini.',
                'date'        => '2026-06-05 09:00:00',
                'location'    => 'Basement Gedung 4',
                'price'       => 25000,
                'stock'       => 8,
                'poster_path' => 'posters/valorant.png',
            ]
        );

        // Seminar (Milik HIMA SI)
        Event::updateOrCreate(
            ['title' => 'Future of AI in Industry'],
            [
                'user_id'     => $himasi->id,
                'category_id' => $seminar->id,
                'description' => 'Membahas dampak AI di dunia kerja tahun 2026.',
                'date'        => '2026-04-30 09:00:00',
                'location'    => 'Cinema Amikom',
                'price'       => 0,
                'stock'       => 150,
                'poster_path' => 'posters/ai.png',
            ]
        );

        Event::updateOrCreate(
            ['title' => 'Digital Marketing 101'],
            [
                'user_id'     => $admin->id,
                'category_id' => $seminar->id,
                'description' => 'Cara jualan produk digital lewat media sosial.',
                'date'        => '2026-05-02 14:00:00',
                'location'    => 'Zoom Meeting',
                'price'       => 0,
                'stock'       => 500,
                'poster_path' => 'posters/marketing.png',
            ]
        );
    }
}

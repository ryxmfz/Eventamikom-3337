<?php

namespace App\Http\Controllers;

use App\Models\Category; // Tambahkan ini untuk memanggil model Kategori
use App\Models\Partner;  // Tambahkan ini untuk memanggil model Partner
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman depan utama (publik) AmikomEventHub.
     */
    public function index()
    {
        // 1. Ambil semua data Partner dan Kategori dari database (Sesuai instruksi Soal 4)
        $partners = Partner::all();
        $categories = Category::all();

        // 2. Kirim data tersebut ke view 'welcome.blade.php' menggunakan fungsi compact
        return view('welcome', compact('partners', 'categories'));
    }
}

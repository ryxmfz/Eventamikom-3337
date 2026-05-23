<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // TAMBAHAN: Untuk membuat slug otomatis dari nama

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori beserta fitur pencarian basic.
     */
    public function index(Request $request)
    {
        // Menangkap input pencarian dari view
        $search = $request->get('search');

        // Jika ada query pencarian, filter data berdasarkan nama
        if ($search) {
            $categories = Category::where('name', 'LIKE', "%{$search}%")->get();
        } else {
            $categories = Category::all();
        }

        // Kirim data ke view index admin
        return view('admin.categories.index', compact('categories', 'search'));
    }

    /**
     * Menyimpan kategori baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        // PERBAIKAN: Menambahkan 'slug' otomatis agar database tidak error
        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    /**
     * Memperbarui data kategori tertentu.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        // PERBAIKAN: Menambahkan 'slug' otomatis saat update data
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Nama kategori berhasil diperbarui!');
    }

    /**
     * Menghapus kategori dari database.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // READ + SEARCH BASIC (Soal 3)
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Sintaks pencarian LIKE sesuai instruksi Soal 3
        $categories = Category::when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', '%' . $search . '%');
        })->latest()->get();

        return view('admin.categories.index', compact('categories', 'search'));
    }

    // CREATE (Soal 1)
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Category::create(['name' => $request->name]);
        return redirect()->back()->with('success', 'Kategori Berhasil Ditambahkan');
    }

    // UPDATE (Soal 1)
    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $category = Category::findOrFail($id);
        $category->update(['name' => $request->name]);
        return redirect()->back()->with('success', 'Kategori Berhasil Diperbarui');
    }

    // DELETE (Soal 1)
    public function destroy($id)
    {
        Category::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Kategori Berhasil Dihapus');
    }
}

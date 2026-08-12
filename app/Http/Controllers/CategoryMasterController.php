<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryMasterController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $categories = Category::withCount('products')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('category-master.index', compact('categories', 'search'));
    }

    public function create()
    {
        return view('category-master.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        Category::create($validated);

        return redirect()
            ->route('category-master.index')
            ->with('status', 'Kategori produk berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('category-master.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('categories', 'name')->ignore($category->id),
            ],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $category->update($validated);

        return redirect()
            ->route('category-master.index')
            ->with('status', 'Kategori produk berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return redirect()
                ->route('category-master.index')
                ->with('error', 'Kategori tidak bisa dihapus karena masih dipakai oleh produk.');
        }

        $category->delete();

        return redirect()
            ->route('category-master.index')
            ->with('status', 'Kategori produk berhasil dihapus.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductMasterController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $products = Product::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('product_code', 'like', "%{$search}%");
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('product-master.index', compact('products', 'search'));
    }

    public function create()
    {
        return view('product-master.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_code' => ['required', 'string', 'max:50', 'unique:products,product_code'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        Product::create($validated);

        return redirect()
            ->route('product-master.index')
            ->with('status', 'Product successfully added.');
    }

    public function edit(Product $product)
    {
        return view('product-master.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_code' => [
                'required', 'string', 'max:50',
                Rule::unique('products', 'product_code')->ignore($product->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $product->update($validated);

        return redirect()
            ->route('product-master.index')
            ->with('status', 'Product successfully updated.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('product-master.index')
            ->with('status', 'Product successfully deleted.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Product, Category, Size};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'sizes']);
        if ($request->search) $query->where('product_name', 'like', '%' . $request->search . '%');
        if ($request->category) $query->where('category_id', $request->category);
        if ($request->filled('active')) $query->where('is_active', (bool) $request->active);
        $products   = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::all();
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        $sizes      = Size::all();
        return view('admin.products.create', compact('categories', 'sizes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'category_id'  => 'required|exists:categories,category_id',
            'description'  => 'nullable|string',
            'product_image'=> 'nullable|image|max:2048',
            'sizes'        => 'required|array',
            'sizes.*.size_id' => 'required|exists:sizes,size_id',
            'sizes.*.price'   => 'required|numeric|min:0',
            'sizes.*.stock'   => 'required|integer|min:0',
        ]);

        $imagePath = null;
        if ($request->hasFile('product_image')) {
            $imagePath = $request->file('product_image')->store('products', 'public');
        }

        $product = Product::create([
            'product_name'  => $request->product_name,
            'product_image' => $imagePath,
            'slug'          => Str::slug($request->product_name) . '-' . uniqid(),
            'description'   => $request->description,
            'category_id'   => $request->category_id,
            'is_featured'   => $request->boolean('is_featured'),
            'is_active'     => $request->boolean('is_active', true),
        ]);

        foreach ($request->sizes as $s) {
            $product->sizes()->attach($s['size_id'], [
                'price'     => $s['price'],
                'stock_qty' => $s['stock'],
            ]);
        }

        return redirect()->route((auth()->user()->isAdmin() ? 'admin' : 'staff').'.products.index')->with('success', 'Product created!');
    }

    public function edit($id)
    {
        $product    = Product::with('sizes')->findOrFail($id);
        $categories = Category::all();
        $sizes      = Size::all();
        return view('admin.products.edit', compact('product', 'categories', 'sizes'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $request->validate([
            'product_name' => 'required|string|max:255',
            'category_id'  => 'required|exists:categories,category_id',
        ]);

        if ($request->hasFile('product_image')) {
            $imagePath = $request->file('product_image')->store('products', 'public');
            $product->product_image = $imagePath;
        }

        $product->update([
            'product_name' => $request->product_name,
            'description'  => $request->description,
            'category_id'  => $request->category_id,
            'is_featured'  => $request->boolean('is_featured'),
            'is_active'    => $request->boolean('is_active'),
        ]);

        if ($request->has('sizes')) {
            $sync = [];
            foreach ($request->sizes as $s) {
                $sync[$s['size_id']] = ['price' => $s['price'], 'stock_qty' => $s['stock']];
            }
            $product->sizes()->sync($sync);
        }

        return redirect()->route((auth()->user()?->isAdmin() ? 'admin' : 'staff').'.products.index')->with('success', 'Product updated!');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return back()->with('success', 'Product deleted.');
    }
}
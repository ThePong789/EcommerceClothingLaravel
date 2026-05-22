<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories.index', [
            'categories' => Category::withCount('products')->paginate(15)
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(['category_name' => 'required|string|max:255']);

        $img = $request->hasFile('category_image')
            ? $request->file('category_image')->store('categories', 'public')
            : null;

        Category::create([
            'category_name' => $request->category_name,
            'category_image' => $img,
            'slug'          => Str::slug($request->category_name),
            'description'   => $request->description,
        ]);

        return back()->with('success', 'Category created!');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['category_name' => 'required|string|max:255']);

        $cat  = Category::findOrFail($id);
        $data = [
            'category_name' => $request->category_name,
            'description'   => $request->description,
            'slug'          => Str::slug($request->category_name),
        ];

        if ($request->hasFile('category_image')) {
            // Delete old image if exists
            if ($cat->category_image) {
                Storage::disk('public')->delete($cat->category_image);
            }
            $data['category_image'] = $request->file('category_image')->store('categories', 'public');
        }

        $cat->update($data);
        return back()->with('success', 'Category updated!');
    }

    public function destroy($id)
    {
        $cat = Category::findOrFail($id);
        if ($cat->category_image) {
            Storage::disk('public')->delete($cat->category_image);
        }
        $cat->delete();
        return back()->with('success', 'Category deleted.');
    }
}
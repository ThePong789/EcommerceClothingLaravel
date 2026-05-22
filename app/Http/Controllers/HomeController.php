<?php

namespace App\Http\Controllers;

use App\Models\{Product, Category, BlogDetail, Review};
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with(['sizes', 'reviews'])
            ->where('is_featured', true)->where('is_active', true)
            ->latest()->take(8)->get();

        $categories = Category::withCount('products')->take(6)->get();

        $latestBlogs = BlogDetail::with('blog')
            ->where('is_published', true)->latest()->take(3)->get();

        $reviews = Review::with('user')->where('is_approved', true)
            ->latest()->take(6)->get();

        return view('home.index', compact('featuredProducts', 'categories', 'latestBlogs', 'reviews'));
    }

    public function shop(Request $request)
    {
        $query = Product::with(['sizes', 'category', 'reviews'])->where('is_active', true);

        if ($request->category) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }
        if ($request->search) {
            $query->where('product_name', 'like', '%' . $request->search . '%');
        }
        if ($request->sort === 'price_asc') {
            $query->join('product_size', 'products.product_id', '=', 'product_size.product_id')
                  ->orderBy('product_size.price');
        } elseif ($request->sort === 'price_desc') {
            $query->join('product_size', 'products.product_id', '=', 'product_size.product_id')
                  ->orderByDesc('product_size.price');
        } else {
            $query->latest('products.created_at');
        }

        $products = $query->select('products.*')->distinct()->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('shop.index', compact('products', 'categories'));
    }

    public function productDetail($id)
    {
        $product = Product::with(['sizes', 'category', 'reviews.user'])->findOrFail($id);
        $related = Product::with('sizes')
            ->where('category_id', $product->category_id)
            ->where('product_id', '!=', $id)
            ->take(4)->get();

        return view('shop.show', compact('product', 'related'));
    }

    public function blog()
    {
        $blogs = BlogDetail::with('blog')->where('is_published', true)->latest()->paginate(9);
        return view('blog.index', compact('blogs'));
    }

    public function blogDetail($id)
    {
        $blog = BlogDetail::with('blog')->findOrFail($id);
        $recent = BlogDetail::with('blog')->where('blog_detail_id', '!=', $id)
            ->where('is_published', true)->latest()->take(4)->get();
        return view('blog.show', compact('blog', 'recent'));
    }

    public function about()
    {
        return view('home.about');
    }

    public function contact()
    {
        return view('home.contact');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Blog, BlogDetail};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = BlogDetail::with('blog')->latest()->paginate(15);
        return view('admin.blog.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blog.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required',
        ]);

        $blog = Blog::create([
            'author_name'  => Auth::user()->username,
            'author_image' => Auth::user()->profile_image,
            'user_id'      => Auth::id(),
        ]);

        $img = $request->hasFile('blog_image') ? $request->file('blog_image')->store('blogs', 'public') : null;

        BlogDetail::create([
            'blog_id'      => $blog->blog_id,
            'title'        => $request->title,
            'subtitle'     => $request->subtitle,
            'description'  => $request->description,
            'blog_image'   => $img,
            'slug'         => Str::slug($request->title) . '-' . uniqid(),
            'tags'         => $request->tags,
            'is_published' => $request->boolean('is_published', true),
        ]);

        return redirect()->route('admin.blog.index')->with('success', 'Blog post created!');
    }

    public function edit($id)
    {
        $blog = BlogDetail::with('blog')->findOrFail($id);
        return view('admin.blog.edit', compact('blog'));
    }

    public function update(Request $request, $id)
    {
        $detail = BlogDetail::findOrFail($id);
        $request->validate(['title' => 'required', 'description' => 'required']);

        if ($request->hasFile('blog_image')) {
            $detail->blog_image = $request->file('blog_image')->store('blogs', 'public');
        }

        $detail->update($request->only('title', 'subtitle', 'description', 'tags', 'is_published'));
        return redirect()->route('admin.blog.index')->with('success', 'Blog post updated!');
    }

    public function destroy($id)
    {
        BlogDetail::findOrFail($id)->delete();
        return back()->with('success', 'Blog post deleted.');
    }
}

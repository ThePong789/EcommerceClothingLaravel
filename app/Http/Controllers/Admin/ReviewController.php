<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'product'])->latest()->paginate(20);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve($id)
    {
        Review::findOrFail($id)->update(['is_approved' => true]);
        return back()->with('success', 'Review approved.');
    }

    public function destroy($id)
    {
        Review::findOrFail($id)->delete();
        return back()->with('success', 'Review deleted.');
    }
}

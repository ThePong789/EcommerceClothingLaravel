<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id'   => 'required|exists:products,product_id',
            'review_title' => 'required|string|max:255',
            'description'  => 'required|string',
            'rating'       => 'required|integer|between:1,5',
        ]);

        Review::create([
            'user_id'      => Auth::id(),
            'product_id'   => $request->product_id,
            'review_title' => $request->review_title,
            'description'  => $request->description,
            'rating'       => $request->rating,
            'is_approved'  => false,
        ]);

        return back()->with('success', 'Review submitted and awaiting approval.');
    }
}

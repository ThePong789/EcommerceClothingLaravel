<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|max:255',
            'subject'    => 'nullable|string|max:255',
            'message'    => 'required|string',
        ]);

        ContactMessage::create($request->only('first_name', 'last_name', 'email', 'subject', 'message'));

        return back()->with('contact_success', true);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;

class ContactController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(20);
        return view('admin.contact.index', compact('messages'));
    }

    public function show($id)
    {
        $msg = ContactMessage::findOrFail($id);
        $msg->update(['is_read' => true]);
        return view('admin.contact.show', compact('msg'));
    }

    public function destroy($id)
    {
        ContactMessage::findOrFail($id)->delete();
        return back()->with('success', 'Message deleted.');
    }
}

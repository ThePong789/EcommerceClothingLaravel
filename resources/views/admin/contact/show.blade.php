@extends('layouts.admin')

@section('title', 'Message from '.$msg->first_name)
@section('page-title', 'Contact Message')
@section('breadcrumb', 'Admin / Contact Messages / View')

@section('content')
<div class="card" style="max-width:700px;">
    <div class="card-header">
        <h3>{{ $msg->first_name }} {{ $msg->last_name }}</h3>
        <a href="{{ route('admin.contact.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
    <div class="card-body" style="font-size:.9rem;line-height:1.8;">
        <p><strong>Email:</strong> <a href="mailto:{{ $msg->email }}">{{ $msg->email }}</a></p>
        <p><strong>Subject:</strong> {{ $msg->subject ?: '—' }}</p>
        <p><strong>Date:</strong> {{ $msg->created_at->format('F d, Y H:i') }}</p>
        <hr style="margin:1.25rem 0;">
        <p style="white-space:pre-line;">{{ $msg->message }}</p>
    </div>
</div>
@endsection

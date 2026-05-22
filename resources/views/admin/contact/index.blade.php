@extends('layouts.admin')

@section('title', 'Contact Messages')
@section('page-title', 'Contact Messages')
@section('breadcrumb', 'Admin / Contact Messages')

@section('content')
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $msg)
                <tr style="{{ !$msg->is_read ? 'font-weight:600;' : '' }}">
                    <td>{{ $msg->first_name }} {{ $msg->last_name }}</td>
                    <td>{{ $msg->email }}</td>
                    <td>{{ $msg->subject ?: '—' }}</td>
                    <td style="color:var(--text-muted);font-size:.8rem;">{{ $msg->created_at->format('M d, Y H:i') }}</td>
                    <td>
                        <span class="badge {{ $msg->is_read ? 'badge-secondary' : 'badge-info' }}">
                            {{ $msg->is_read ? 'Read' : 'New' }}
                        </span>
                    </td>
                    <td style="display:flex;gap:.4rem;">
                        <a href="{{ route('admin.contact.show', $msg->message_id) }}" class="btn btn-outline btn-xs"><i class="fas fa-eye"></i> View</a>
                        <form action="{{ route('admin.contact.destroy', $msg->message_id) }}" method="POST" onsubmit="return confirm('Delete this message?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-xs" type="submit"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state"><i class="fas fa-envelope-open"></i><h3>No messages yet.</h3></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div style="margin-top:1rem;">{{ $messages->links() }}</div>
@endsection

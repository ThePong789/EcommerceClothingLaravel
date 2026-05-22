@extends('layouts.admin')

@section('title', 'Blog')
@section('page-title', 'Blog Posts')
@section('breadcrumb', 'Admin / Blog')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
    <div></div>
    <a href="{{ route('admin.blog.create') }}" class="btn btn-accent"><i class="fas fa-plus"></i> New Post</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Post</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($blogs as $blog)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:.75rem;">
                            <div style="width:52px;height:40px;background:var(--bg);border-radius:6px;overflow:hidden;flex-shrink:0;display:flex;align-items:center;justify-content:center;color:var(--text-muted);">
                                @if($blog->blog_image)
                                    <img src="{{ asset('storage/'.$blog->blog_image) }}" style="width:100%;height:100%;object-fit:cover;">
                                @else <i class="fas fa-newspaper"></i> @endif
                            </div>
                            <div>
                                <div style="font-weight:500;font-size:.875rem;">{{ $blog->title }}</div>
                                <div style="font-size:.75rem;color:var(--text-muted);">{{ Str::limit($blog->subtitle, 50) }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:.875rem;">{{ $blog->blog->author_name ?? 'N/A' }}</td>
                    <td><span class="badge {{ $blog->is_published ? 'badge-success' : 'badge-secondary' }}">{{ $blog->is_published ? 'Published' : 'Draft' }}</span></td>
                    <td style="color:var(--text-muted);font-size:.8rem;">{{ $blog->created_at->format('M d, Y') }}</td>
                    <td>
                        <div style="display:flex;gap:.4rem;">
                            <a href="{{ route('blog.show', $blog->blog_detail_id) }}" target="_blank" class="btn btn-outline btn-xs"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.blog.edit', $blog->blog_detail_id) }}" class="btn btn-outline btn-xs"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.blog.destroy', $blog->blog_detail_id) }}" method="POST" onsubmit="return confirm('Delete post?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-xs" type="submit"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5"><div class="empty-state"><i class="fas fa-newspaper"></i><h3>No blog posts yet</h3><a href="{{ route('admin.blog.create') }}" class="btn btn-accent" style="margin-top:1rem;">Create First Post</a></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div style="margin-top:1rem;">{{ $blogs->links() }}</div>
@endsection

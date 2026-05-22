@extends('layouts.admin')

@section('title', 'New Blog Post')
@section('page-title', 'New Blog Post')
@section('breadcrumb', 'Admin / Blog / Create')

@section('content')
<form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row col-2" style="align-items:start;">
        <div style="display:flex;flex-direction:column;gap:1.5rem;">
            <div class="card">
                <div class="card-header"><h3>Post Content</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Subtitle</label>
                        <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Content *</label>
                        <textarea name="description" class="form-control" rows="12" required>{{ old('description') }}</textarea>
                    </div>
                    {{-- <div class="form-group">
                        <label class="form-label">Tags <span style="color:var(--text-muted);font-weight:400;">(comma separated)</span></label>
                        <input type="text" name="tags" class="form-control" value="{{ old('tags') }}" placeholder="fashion, style, trends">
                    </div> --}}
                </div>
            </div>
        </div>
        <div style="display:flex;flex-direction:column;gap:1.5rem;">
            <div class="card">
                <div class="card-header"><h3>Featured Image</h3></div>
                <div class="card-body">
                    <div id="blog-img-preview" style="width:100%;height:180px;background:var(--bg);border-radius:8px;border:2px dashed var(--border);display:flex;align-items:center;justify-content:center;margin-bottom:1rem;overflow:hidden;cursor:pointer;" onclick="document.getElementById('blogImgInput').click()">
                        <div style="text-align:center;color:var(--text-muted);">
                            <i class="fas fa-image" style="font-size:2rem;margin-bottom:.5rem;display:block;"></i>
                            <div style="font-size:.85rem;">Click to upload</div>
                        </div>
                    </div>
                    <input type="file" id="blogImgInput" name="blog_image" accept="image/*" style="display:none;" onchange="previewBlogImg(this)">
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;margin-bottom:1rem;">
                        <input type="checkbox" name="is_published" value="1" checked> Publish immediately
                    </label>
                    <button type="submit" class="btn btn-accent" style="width:100%;justify-content:center;"><i class="fas fa-save"></i> Save Post</button>
                    <a href="{{ route('admin.blog.index') }}" class="btn btn-outline" style="width:100%;justify-content:center;margin-top:.5rem;">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
@push('scripts')
<script>
function previewBlogImg(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('blog-img-preview').innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush

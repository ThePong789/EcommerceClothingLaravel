@extends('layouts.admin')

@section('title', 'Categories')
@section('page-title', 'Categories')
@section('breadcrumb', 'Admin / Categories')

@section('content')
@php $routePrefix = auth()->user()->isAdmin() ? 'admin' : 'staff'; @endphp



<div class="row col-2" style="align-items:start;">

    <!-- ADD CATEGORY -->
    <div class="card">
        <div class="card-header"><h3>Add Category</h3></div>
        <div class="card-body">
            <form action="{{ route($routePrefix.'.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label">Category Name *</label>
                    <input type="text" name="category_name" class="form-control" value="{{ old('category_name') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Image</label>
                    <div id="add-img-preview" style="width:100%;height:200px;background:var(--bg);border-radius:8px;border:2px dashed var(--border);display:flex;align-items:center;justify-content:center;margin-bottom:.75rem;overflow:hidden;cursor:pointer;" onclick="document.getElementById('add-img-input').click()">
                        <div style="text-align:center;color:var(--text-muted);">
                            <i class="fas fa-cloud-upload-alt" style="font-size:2rem;margin-bottom:.5rem;display:block;"></i>
                            <div style="font-size:.85rem;">Click to upload image</div>
                        </div>
                    </div>
                    <input type="file" id="add-img-input" name="category_image" accept="image/*" style="display:none;" onchange="previewAddImg(this)">
                    <label style="font-size:.8rem;color:var(--text-muted);">JPG, PNG up to 2MB</label>
                </div>
                <button type="submit" class="btn btn-accent" style="width:100%;justify-content:center;"><i class="fas fa-plus"></i> Add Category</button>
            </form>
        </div>
    </div>

    <!-- CATEGORY LIST -->
    <div class="card">
        <div class="card-header"><h3>All Categories</h3></div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Products</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:.75rem;">
                                <div style="width:40px;height:40px;background:var(--bg);border-radius:8px;overflow:hidden;flex-shrink:0;display:flex;align-items:center;justify-content:center;color:var(--text-muted);">
                                    @if($cat->category_image)
                                        <img src="{{ asset('storage/'.$cat->category_image) }}" style="width:100%;height:100%;object-fit:cover;">
                                    @else <i class="fas fa-tag"></i> @endif
                                </div>
                                <div>
                                    <div style="font-weight:500;font-size:.875rem;">{{ $cat->category_name }}</div>
                                    <div style="font-size:.75rem;color:var(--text-muted);">{{ Str::limit($cat->description, 40) }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge badge-info">{{ $cat->products_count }}</span></td>
                        <td>
                            <div style="display:flex;gap:.4rem;">
                                <button class="btn btn-outline btn-xs" onclick="editCat({{ $cat->category_id }}, '{{ addslashes($cat->category_name) }}', '{{ addslashes($cat->description ?? '') }}', '{{ $cat->category_image ? asset('storage/'.$cat->category_image) : '' }}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route($routePrefix.'.categories.destroy', $cat->category_id) }}" method="POST" onsubmit="return confirm('Delete category?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-xs" type="submit"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3"><div class="empty-state" style="padding:2rem;"><i class="fas fa-tags"></i><h3>No categories yet</h3></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:1rem;">{{ $categories->links() }}</div>
    </div>
</div>

<!-- EDIT MODAL -->
<div id="edit-cat-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:12px;padding:2rem;width:460px;max-width:95vw;max-height:90vh;overflow-y:auto;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
            <h3>Edit Category</h3>
            <button onclick="closeEditModal()" style="background:none;border:none;font-size:1.2rem;cursor:pointer;color:var(--text-muted);">&times;</button>
        </div>
        <form id="edit-cat-form" method="POST" enctype="multipart/form-data">
            @csrf @method('PATCH')
            <div class="form-group">
                <label class="form-label">Category Name *</label>
                <input type="text" name="category_name" id="edit-cat-name" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" id="edit-cat-desc" class="form-control" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Image</label>
                <div id="edit-img-preview" style="width:100%;height:200px;background:var(--bg);border-radius:8px;border:2px dashed var(--border);display:flex;align-items:center;justify-content:center;margin-bottom:.75rem;overflow:hidden;cursor:pointer;" onclick="document.getElementById('edit-img-input').click()">
                    <div style="text-align:center;color:var(--text-muted);">
                        <i class="fas fa-cloud-upload-alt" style="font-size:2rem;margin-bottom:.5rem;display:block;"></i>
                        <div style="font-size:.85rem;">Click to change image</div>
                    </div>
                </div>
                <input type="file" id="edit-img-input" name="category_image" accept="image/*" style="display:none;" onchange="previewEditImg(this)">
                <label style="font-size:.8rem;color:var(--text-muted);">Leave empty to keep current image. JPG, PNG up to 2MB.</label>
            </div>
            <div style="display:flex;gap:.75rem;margin-top:1.5rem;">
                <button type="submit" class="btn btn-accent" style="flex:1;justify-content:center;"><i class="fas fa-save"></i> Update</button>
                <button type="button" class="btn btn-outline" onclick="closeEditModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
const routePrefix = '{{ $routePrefix }}';

function previewAddImg(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('add-img-preview').innerHTML =
                `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewEditImg(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('edit-img-preview').innerHTML =
                `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function editCat(id, name, desc, imgUrl) {
    // Set form action dynamically for both admin and staff
    const base = routePrefix === 'admin' ? '/admin/categories/' : '/staff/categories/';
    document.getElementById('edit-cat-form').action = base + id;
    document.getElementById('edit-cat-name').value = name;
    document.getElementById('edit-cat-desc').value = desc;

    // Reset file input
    document.getElementById('edit-img-input').value = '';

    // Show current image or placeholder
    const preview = document.getElementById('edit-img-preview');
    if (imgUrl) {
        preview.innerHTML = `<img src="${imgUrl}" style="width:100%;height:100%;object-fit:cover;">`;
    } else {
        preview.innerHTML = `
            <div style="text-align:center;color:var(--text-muted);">
                <i class="fas fa-cloud-upload-alt" style="font-size:2rem;margin-bottom:.5rem;display:block;"></i>
                <div style="font-size:.85rem;">Click to upload image</div>
            </div>`;
    }

    document.getElementById('edit-cat-modal').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('edit-cat-modal').style.display = 'none';
}

// Close modal when clicking backdrop
document.getElementById('edit-cat-modal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});
</script>
@endpush
@extends('layouts.admin')

@section('title', 'Users')
@section('page-title', 'Users')
@section('breadcrumb', 'Admin / Users')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
    <form method="GET" style="display:flex;gap:.75rem;">
        <input type="text" name="search" class="form-control" placeholder="Search users..." value="{{ request('search') }}" style="width:220px;">
        <select name="role" class="form-control" style="width:140px;">
            <option value="">All Roles</option>
            @foreach($roles as $role)
                <option value="{{ $role->role_id }}" {{ request('role') == $role->role_id ? 'selected' : '' }}>{{ $role->role_name }}</option>
            @endforeach
        </select>
        <button class="btn btn-outline" type="submit"><i class="fas fa-search"></i></button>
    </form>
    <button class="btn btn-accent" onclick="document.getElementById('add-user-modal').style.display='flex'"><i class="fas fa-plus"></i> Add User</button>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:.75rem;">
                            <div style="width:36px;height:36px;background:var(--accent-light);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;color:var(--accent);font-size:.85rem;">
                                {{ strtoupper(substr($user->username,0,1)) }}
                            </div>
                            <div>
                                <div style="font-weight:500;font-size:.875rem;">{{ $user->username }}</div>
                                <div style="font-size:.75rem;color:var(--text-muted);">{{ $user->first_name }} {{ $user->last_name }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:.875rem;">{{ $user->email }}</td>
                    <td>
                        @php $roleColors = [1=>'danger',2=>'info',3=>'secondary']; @endphp
                        <span class="badge badge-{{ $roleColors[$user->role_id] ?? 'secondary' }}">{{ $user->role->role_name ?? 'N/A' }}</span>
                    </td>
                    <td style="color:var(--text-muted);font-size:.8rem;">{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        <div style="display:flex;gap:.4rem;">
                            <button class="btn btn-outline btn-xs" onclick="openEditUser({{ $user->user_id }}, {{ $user->role_id }})"><i class="fas fa-edit"></i></button>
                            @if($user->user_id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user->user_id) }}" method="POST" onsubmit="return confirm('Delete this user?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-xs" type="submit"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5"><div class="empty-state"><i class="fas fa-users"></i><h3>No users found</h3></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div style="margin-top:1rem;">{{ $users->links() }}</div>

<!-- ADD USER MODAL -->
<div id="add-user-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:12px;padding:2rem;width:440px;max-width:95vw;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
            <h3>Add New User</h3>
            <button onclick="document.getElementById('add-user-modal').style.display='none'" style="background:none;border:none;font-size:1.2rem;cursor:pointer;color:var(--text-muted);">&times;</button>
        </div>
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Username *</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Password *</label>
                <input type="password" name="password" class="form-control" required minlength="6">
            </div>
            <div class="form-group">
                <label class="form-label">Role *</label>
                <select name="role_id" class="form-control" required>
                    @foreach($roles as $role)
                        <option value="{{ $role->role_id }}">{{ $role->role_name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;gap:.75rem;margin-top:1.5rem;">
                <button type="submit" class="btn btn-accent" style="flex:1;justify-content:center;">Create User</button>
                <button type="button" class="btn btn-outline" onclick="document.getElementById('add-user-modal').style.display='none'">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT USER MODAL -->
<div id="edit-user-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:12px;padding:2rem;width:380px;max-width:95vw;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
            <h3>Edit User Role</h3>
            <button onclick="document.getElementById('edit-user-modal').style.display='none'" style="background:none;border:none;font-size:1.2rem;cursor:pointer;color:var(--text-muted);">&times;</button>
        </div>
        <form id="edit-user-form" method="POST">
            @csrf @method('PATCH')
            <div class="form-group">
                <label class="form-label">Role</label>
                <select name="role_id" id="edit-role" class="form-control">
                    @foreach($roles as $role)
                        <option value="{{ $role->role_id }}">{{ $role->role_name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;gap:.75rem;margin-top:1.5rem;">
                <button type="submit" class="btn btn-accent" style="flex:1;justify-content:center;">Update</button>
                <button type="button" class="btn btn-outline" onclick="document.getElementById('edit-user-modal').style.display='none'">Cancel</button>
            </div>
        </form>
    </div>
</div>

@endsection
@push('scripts')
<script>
function openEditUser(userId, roleId) {
    document.getElementById('edit-user-form').action = '/admin/users/' + userId;
    document.getElementById('edit-role').value = roleId;
    document.getElementById('edit-user-modal').style.display = 'flex';
}
</script>
@endpush

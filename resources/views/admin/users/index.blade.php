@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('header_title', 'Kelola Pengguna')
@section('header_subtitle', 'Manajemen hak akses dan akun pengguna sistem.')

@section('content')
<div class="content-panel glass-panel">
    <div class="panel-header">
        <h3 class="panel-title">Daftar Pengguna</h3>
        <button class="btn btn-primary btn-sm" onclick="openCreateModal()">Tambah Pengguna</button>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Peran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                    <tr>
                        <td><strong>{{ $u->name }}</strong></td>
                        <td>{{ $u->email }}</td>
                        <td>
                            <span class="badge badge-info">{{ str_replace('_', ' ', $u->role) }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-warning btn-xs" onclick="openEditModal({{ json_encode($u) }})">
                                    Edit
                                </button>
                                @if($u->id !== Auth::id())
                                    <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs">Hapus</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Create Modal -->
<div id="createModal" class="modal-overlay" style="display: none;">
    <div class="modal-content glass-panel">
        <div class="modal-header">
            <h3 class="panel-title">Tambah Pengguna Baru</h3>
            <button class="modal-close" onclick="closeCreateModal()">&times;</button>
        </div>
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" required placeholder="Nama Lengkap">
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required placeholder="email@domain.com">
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required minlength="6" placeholder="Minimal 6 karakter">
            </div>
            <div class="form-group">
                <label class="form-label">Peran</label>
                <select name="role" class="form-control" required>
                    <option value="admin">Administrator</option>
                    <option value="kalab">Kepala Lab</option>
                    <option value="kaprodi">Kaprodi</option>
                    <option value="staf_admin">Staf Administrasi</option>
                    <option value="staf_lab">Staf Laboratorium</option>
                </select>
            </div>
            <div class="d-flex justify-end gap-2 mt-4" style="justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeCreateModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal-overlay" style="display: none;">
    <div class="modal-content glass-panel">
        <div class="modal-header">
            <h3 class="panel-title">Ubah Data Pengguna</h3>
            <button class="modal-close" onclick="closeEditModal()">&times;</button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Nama</label>
                <input type="text" name="name" id="edit_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" id="edit_email" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Password Baru (Opsional)</label>
                <input type="password" name="password" class="form-control" minlength="6" placeholder="Biarkan kosong jika tidak diubah">
            </div>
            <div class="form-group">
                <label class="form-label">Peran</label>
                <select name="role" id="edit_role" class="form-control" required>
                    <option value="admin">Administrator</option>
                    <option value="kalab">Kepala Lab</option>
                    <option value="kaprodi">Kaprodi</option>
                    <option value="staf_admin">Staf Administrasi</option>
                    <option value="staf_lab">Staf Laboratorium</option>
                </select>
            </div>
            <div class="d-flex justify-end gap-2 mt-4" style="justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openCreateModal() {
        document.getElementById('createModal').style.display = 'flex';
    }
    function closeCreateModal() {
        document.getElementById('createModal').style.display = 'none';
    }
    function openEditModal(user) {
        document.getElementById('editForm').action = "/admin/users/" + user.id;
        document.getElementById('edit_name').value = user.name;
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_role').value = user.role;
        document.getElementById('editModal').style.display = 'flex';
    }
    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }
</script>
@endsection

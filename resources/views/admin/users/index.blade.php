@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('header_title', 'Kelola Pengguna')

@section('content')
<div class="card card-lg shadow-sm">
    <div class="card-header border-bottom-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0">Daftar Pengguna</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">Tambah Pengguna</button>
    </div>
    
    <div class="table-responsive">
        <table class="table text-nowrap mb-0 table-centered table-hover">
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
                            <span class="badge text-info-emphasis bg-info-subtle text-uppercase">{{ str_replace('_', ' ', $u->role) }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-white btn-sm" onclick="openEditModal({{ json_encode($u) }})">
                                    Edit
                                </button>
                                @if($u->id !== Auth::id())
                                    <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
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
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="createModalLabel">Tambah Pengguna Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="modal-body d-flex flex-column gap-3">
                    <div>
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" required placeholder="Nama Lengkap">
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required placeholder="email@domain.com">
                    </div>
                    <div>
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required minlength="6" placeholder="Minimal 6 karakter">
                    </div>
                    <div>
                        <label class="form-label">Peran</label>
                        <select name="role" class="form-select" required>
                            <option value="admin">Administrator</option>
                            <option value="kalab">Kepala Lab</option>
                            <option value="kaprodi">Kaprodi</option>
                            <option value="staf_admin">Staf Administrasi</option>
                            <option value="staf_lab">Staf Laboratorium</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="editModalLabel">Ubah Data Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body d-flex flex-column gap-3">
                    <div>
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">Password Baru (Opsional)</label>
                        <input type="password" name="password" class="form-control" minlength="6" placeholder="Biarkan kosong jika tidak diubah">
                    </div>
                    <div>
                        <label class="form-label">Peran</label>
                        <select name="role" id="edit_role" class="form-select" required>
                            <option value="admin">Administrator</option>
                            <option value="kalab">Kepala Lab</option>
                            <option value="kaprodi">Kaprodi</option>
                            <option value="staf_admin">Staf Administrasi</option>
                            <option value="staf_lab">Staf Laboratorium</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openEditModal(user) {
        document.getElementById('editForm').action = "/admin/users/" + user.id;
        document.getElementById('edit_name').value = user.name;
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_role').value = user.role;
        
        var editModalEl = document.getElementById('editModal');
        var modalInstance = new bootstrap.Modal(editModalEl);
        modalInstance.show();
    }
</script>
@endsection

@extends('layouts.app')

@section('title', 'Kelola Ruangan')

@section('header_title', 'Kelola Ruangan')
@section('header_subtitle', 'Manajemen daftar ruangan laboratorium di lingkungan kampus.')

@section('content')
<div class="content-panel glass-panel">
    <div class="panel-header">
        <h3 class="panel-title">Daftar Ruangan</h3>
        <button class="btn btn-primary btn-sm" onclick="openCreateModal()">Tambah Ruangan</button>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Kode Ruangan</th>
                    <th>Nama Ruangan</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rooms as $r)
                    <tr>
                        <td><code>{{ $r->code }}</code></td>
                        <td><strong>{{ $r->name }}</strong></td>
                        <td>{{ $r->description ?? '-' }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-warning btn-xs" onclick="openEditModal({{ json_encode($r) }})">
                                    Edit
                                </button>
                                <form action="{{ route('admin.rooms.destroy', $r->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ruangan ini? Semua data relasi aset mungkin terdampak.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs">Hapus</button>
                                </form>
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
            <h3 class="panel-title">Tambah Ruangan Baru</h3>
            <button class="modal-close" onclick="closeCreateModal()">&times;</button>
        </div>
        <form action="{{ route('admin.rooms.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Kode Ruangan</label>
                <input type="text" name="code" class="form-control" required placeholder="Contoh: LAB-KOM-1">
            </div>
            <div class="form-group">
                <label class="form-label">Nama Ruangan</label>
                <input type="text" name="name" class="form-control" required placeholder="Contoh: Lab Jaringan">
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi ruangan..."></textarea>
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
            <h3 class="panel-title">Ubah Data Ruangan</h3>
            <button class="modal-close" onclick="closeEditModal()">&times;</button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Kode Ruangan</label>
                <input type="text" name="code" id="edit_code" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Nama Ruangan</label>
                <input type="text" name="name" id="edit_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
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
    function openEditModal(room) {
        document.getElementById('editForm').action = "/admin/rooms/" + room.id;
        document.getElementById('edit_code').value = room.code;
        document.getElementById('edit_name').value = room.name;
        document.getElementById('edit_description').value = room.description || '';
        document.getElementById('editModal').style.display = 'flex';
    }
    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }
</script>
@endsection

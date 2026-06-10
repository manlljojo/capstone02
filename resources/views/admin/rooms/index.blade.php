@extends('layouts.app')

@section('title', 'Kelola Ruangan')

@section('header_title', 'Kelola Ruangan')

@section('content')
<div class="card card-lg shadow-sm">
    <div class="card-header border-bottom-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0">Daftar Ruangan</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">Tambah Ruangan</button>
    </div>
    
    <div class="table-responsive">
        <table class="table text-nowrap mb-0 table-centered table-hover">
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
                                <button class="btn btn-white btn-sm" onclick="openEditModal({{ json_encode($r) }})">
                                    Edit
                                </button>
                                <form action="{{ route('admin.rooms.destroy', $r->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ruangan ini? Semua data relasi aset mungkin terdampak.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
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
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="createModalLabel">Tambah Ruangan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.rooms.store') }}" method="POST">
                @csrf
                <div class="modal-body d-flex flex-column gap-3">
                    <div>
                        <label class="form-label">Kode Ruangan</label>
                        <input type="text" name="code" class="form-control" required placeholder="Contoh: LAB-KOM-1">
                    </div>
                    <div>
                        <label class="form-label">Nama Ruangan</label>
                        <input type="text" name="name" class="form-control" required placeholder="Contoh: Lab Jaringan">
                    </div>
                    <div>
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi ruangan..."></textarea>
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
                <h5 class="modal-title fw-bold" id="editModalLabel">Ubah Data Ruangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body d-flex flex-column gap-3">
                    <div>
                        <label class="form-label">Kode Ruangan</label>
                        <input type="text" name="code" id="edit_code" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">Nama Ruangan</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
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
    function openEditModal(room) {
        document.getElementById('editForm').action = "/admin/rooms/" + room.id;
        document.getElementById('edit_code').value = room.code;
        document.getElementById('edit_name').value = room.name;
        document.getElementById('edit_description').value = room.description || '';
        
        var editModalEl = document.getElementById('editModal');
        var modalInstance = new bootstrap.Modal(editModalEl);
        modalInstance.show();
    }
</script>
@endsection

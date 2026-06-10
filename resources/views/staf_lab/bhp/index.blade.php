@extends('layouts.app')

@section('title', 'Kelola Stok BHP')

@section('header_title', 'Stok Barang Habis Pakai (BHP)')
@section('header_subtitle', 'Manajemen ketersediaan barang habis pakai laboratorium.')

@section('content')
<div class="content-panel glass-panel">
    <div class="panel-header">
        <h3 class="panel-title">Daftar Stok BHP</h3>
        <button class="btn btn-primary btn-sm" onclick="openCreateModal()">Tambah BHP</button>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Jumlah Stok</th>
                    <th>Satuan</th>
                    <th>Status Stok</th>
                    <th>Terakhir Diubah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bhps as $b)
                    <tr>
                        <td><strong>{{ $b->name }}</strong></td>
                        <td>
                            <span class="badge {{ $b->stock < 5 ? 'badge-danger' : 'badge-success' }}" style="font-size: 0.95rem; font-weight:700;">
                                {{ $b->stock }}
                            </span>
                        </td>
                        <td><code>{{ $b->unit }}</code></td>
                        <td>
                            @if($b->stock == 0)
                                <span class="badge badge-danger">Habis</span>
                            @elseif($b->stock < 5)
                                <span class="badge badge-warning">Hampir Habis</span>
                            @else
                                <span class="badge badge-success">Tersedia</span>
                            @endif
                        </td>
                        <td>{{ $b->updated_at->format('d M Y H:i') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-warning btn-xs" onclick="openEditModal({{ json_encode($b) }})">
                                    Sesuaikan Stok
                                </button>
                                <form action="{{ route('staf_lab.bhp.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus BHP ini?')">
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
            <h3 class="panel-title">Tambah BHP Baru</h3>
            <button class="modal-close" onclick="closeCreateModal()">&times;</button>
        </div>
        <form action="{{ route('staf_lab.bhp.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Barang</label>
                <input type="text" name="name" class="form-control" required placeholder="Contoh: Alkohol Swab 70%">
            </div>
            
            <div class="grid-2" style="margin-bottom:0;">
                <div class="form-group">
                    <label class="form-label">Jumlah Stok Awal</label>
                    <input type="number" name="stock" class="form-control" required min="0" placeholder="Contoh: 10">
                </div>
                <div class="form-group">
                    <label class="form-label">Satuan</label>
                    <input type="text" name="unit" class="form-control" required placeholder="Contoh: box, pcs, roll">
                </div>
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
            <h3 class="panel-title">Penyesuaian Stok BHP</h3>
            <button class="modal-close" onclick="closeEditModal()">&times;</button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Nama Barang</label>
                <input type="text" name="name" id="edit_name" class="form-control" required>
            </div>
            
            <div class="grid-2" style="margin-bottom:0;">
                <div class="form-group">
                    <label class="form-label">Jumlah Stok</label>
                    <input type="number" name="stock" id="edit_stock" class="form-control" required min="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Satuan</label>
                    <input type="text" name="unit" id="edit_unit" class="form-control" required>
                </div>
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
    function openEditModal(bhp) {
        document.getElementById('editForm').action = "/staf-lab/bhp/" + bhp.id;
        document.getElementById('edit_name').value = bhp.name;
        document.getElementById('edit_stock').value = bhp.stock;
        document.getElementById('edit_unit').value = bhp.unit;
        document.getElementById('editModal').style.display = 'flex';
    }
    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }
</script>
@endsection

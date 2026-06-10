@extends('layouts.app')

@section('title', 'Kelola Stok BHP')

@section('header_title', 'Stok Barang Habis Pakai (BHP)')

@section('content')
<div class="card card-lg shadow-sm">
    <div class="card-header border-bottom-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0">Daftar Stok BHP</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">Tambah BHP</button>
    </div>
    
    <div class="table-responsive">
        <table class="table text-nowrap mb-0 table-centered table-hover">
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
                            @if($b->stock < 5)
                                <span class="badge text-danger-emphasis bg-danger-subtle" style="font-size: 0.9rem;">{{ $b->stock }}</span>
                            @else
                                <span class="badge text-success-emphasis bg-success-subtle" style="font-size: 0.9rem;">{{ $b->stock }}</span>
                            @endif
                        </td>
                        <td><code>{{ $b->unit }}</code></td>
                        <td>
                            @if($b->stock == 0)
                                <span class="badge text-danger-emphasis bg-danger-subtle">Habis</span>
                            @elseif($b->stock < 5)
                                <span class="badge text-warning-emphasis bg-warning-subtle">Hampir Habis</span>
                            @else
                                <span class="badge text-success-emphasis bg-success-subtle">Tersedia</span>
                            @endif
                        </td>
                        <td>{{ $b->updated_at->format('d M Y H:i') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-white btn-sm" onclick="openEditModal({{ json_encode($b) }})">
                                    Sesuaikan Stok
                                </button>
                                <form action="{{ route('staf_lab.bhp.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus BHP ini?')">
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
                <h5 class="modal-title fw-bold" id="createModalLabel">Tambah BHP Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('staf_lab.bhp.store') }}" method="POST">
                @csrf
                <div class="modal-body d-flex flex-column gap-3">
                    <div>
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="name" class="form-control" required placeholder="Contoh: Alkohol Swab 70%">
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-6 col-12">
                            <label class="form-label">Jumlah Stok Awal</label>
                            <input type="number" name="stock" class="form-control" required min="0" placeholder="Contoh: 10">
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label">Satuan</label>
                            <input type="text" name="unit" class="form-control" required placeholder="Contoh: box, pcs, roll">
                        </div>
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
                <h5 class="modal-title fw-bold" id="editModalLabel">Penyesuaian Stok BHP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body d-flex flex-column gap-3">
                    <div>
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-6 col-12">
                            <label class="form-label">Jumlah Stok</label>
                            <input type="number" name="stock" id="edit_stock" class="form-control" required min="0">
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label">Satuan</label>
                            <input type="text" name="unit" id="edit_unit" class="form-control" required>
                        </div>
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
    function openEditModal(bhp) {
        document.getElementById('editForm').action = "/staf-lab/bhp/" + bhp.id;
        document.getElementById('edit_name').value = bhp.name;
        document.getElementById('edit_stock').value = bhp.stock;
        document.getElementById('edit_unit').value = bhp.unit;
        
        var editModalEl = document.getElementById('editModal');
        var modalInstance = new bootstrap.Modal(editModalEl);
        modalInstance.show();
    }
</script>
@endsection

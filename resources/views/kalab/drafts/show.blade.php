@extends('layouts.app')

@section('title', 'Detail Draf Pengadaan')

@section('header_title', 'Draf Pengadaan ' . $draft->year)

@section('content')
<div class="mb-4">
    <a href="{{ route('kalab.drafts.index') }}" class="btn btn-white btn-sm d-inline-flex align-items-center gap-1">
        <i class="ti ti-arrow-left"></i> Kembali ke Draf
    </a>
</div>

<div class="row g-6 mb-6">
    <div class="col-md-4 col-sm-12">
        <div class="card card-lg shadow-sm">
            <div class="card-body">
                <div class="text-secondary fw-semibold mb-2">Tahun Anggaran</div>
                <div class="fs-3 fw-bold text-dark">Tahun {{ $draft->year }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="card card-lg shadow-sm">
            <div class="card-body">
                <div class="text-secondary fw-semibold mb-2">Status Draf</div>
                <div class="fs-3 fw-bold text-uppercase text-dark">{{ str_replace('_', ' ', $draft->status) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="card card-lg shadow-sm">
            <div class="card-body">
                <div class="text-secondary fw-semibold mb-2">Estimasi Total Biaya</div>
                <div class="fs-3 fw-bold text-dark">Rp {{ number_format($items->sum(function($item) { return $item->price * $item->quantity; }), 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card card-lg shadow-sm">
    <div class="card-header border-bottom-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0">Daftar Barang Pengadaan</h5>
        @if($draft->status == 'draft')
            <button class="btn btn-primary btn-sm" onclick="openCreateModal()">Tambah Barang</button>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table text-nowrap mb-0 table-centered table-hover">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Tipe</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                    <th>Link Pembelian</th>
                    <th>Menggantikan Aset</th>
                    <th>Status Review</th>
                    @if($draft->status == 'draft')
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td><strong>{{ $item->name }}</strong></td>
                        <td>
                            @if($item->type == 'asset')
                                <span class="badge text-info-emphasis bg-info-subtle">Aset</span>
                            @else
                                <span class="badge text-success-emphasis bg-success-subtle">BHP</span>
                            @endif
                        </td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        <td>
                            @if($item->purchase_link)
                                <a href="{{ $item->purchase_link }}" target="_blank" class="btn btn-white btn-sm">
                                    Buka Link
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($item->replacedAsset)
                                <span class="small text-secondary">
                                    {{ $item->replacedAsset->name }} <br>
                                    <code>({{ $item->replacedAsset->code }})</code>
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($item->status == 'pending')
                                <span class="badge text-warning-emphasis bg-warning-subtle">Pending</span>
                            @elseif($item->status == 'approved')
                                <span class="badge text-success-emphasis bg-success-subtle">Disetujui</span>
                            @else
                                <span class="badge text-danger-emphasis bg-danger-subtle">Ditolak</span>
                            @endif
                        </td>
                        @if($draft->status == 'draft')
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-white btn-sm" onclick="openEditModal({{ json_encode($item) }})">
                                        Edit
                                    </button>
                                    <form action="{{ route('kalab.drafts.items.destroy', [$draft->id, $item->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $draft->status == 'draft' ? 9 : 8 }}" class="text-center text-secondary py-4">
                            Belum ada barang dalam draf pengadaan ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($draft->status == 'draft')
<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="createModalLabel">Tambah Barang Pengadaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('kalab.drafts.items.store', $draft->id) }}" method="POST">
                @csrf
                <div class="modal-body d-flex flex-column gap-3">
                    <div>
                        <label class="form-label">Tipe Barang</label>
                        <select name="type" id="create_type" class="form-select" onchange="toggleReplacedAssetField('create')" required>
                            <option value="asset">Aset / Inventaris (Komputer, Mikroskop, dll)</option>
                            <option value="bhp">Barang Habis Pakai / BHP (Kabel, Masker, Hand Sanitizer, dll)</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="name" class="form-control" required placeholder="Contoh: Mikroskop Olympus BX53">
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6 col-12">
                            <label class="form-label">Harga Estimasi (Satuan)</label>
                            <input type="number" name="price" class="form-control" required placeholder="Contoh: 1500000">
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label">Jumlah</label>
                            <input type="number" name="quantity" class="form-control" required min="1" placeholder="Contoh: 2">
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Link Pembelian (Opsional)</label>
                        <input type="url" name="purchase_link" class="form-control" placeholder="https://tokopedia.com/...">
                    </div>

                    <div id="create_replaced_asset_group">
                        <label class="form-label">Menggantikan Aset Lama (Opsional)</label>
                        <select name="replaced_asset_id" class="form-select">
                            <option value="">-- Tidak menggantikan aset apapun --</option>
                            @foreach($assets as $asset)
                                <option value="{{ $asset->id }}">
                                    {{ $asset->name }} [{{ $asset->code ?? 'BELUM DILABEL' }}] (Kondisi: {{ $asset->status }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-secondary small mt-1">
                            *Jika dipilih, aset lama yang digantikan akan diarsipkan ketika barang baru ini diterima oleh staf admin.
                        </p>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah Barang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="editModalLabel">Ubah Barang Pengadaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body d-flex flex-column gap-3">
                    <div>
                        <label class="form-label">Tipe Barang</label>
                        <select name="type" id="edit_type" class="form-select" onchange="toggleReplacedAssetField('edit')" required>
                            <option value="asset">Aset / Inventaris</option>
                            <option value="bhp">Barang Habis Pakai / BHP</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6 col-12">
                            <label class="form-label">Harga Estimasi (Satuan)</label>
                            <input type="number" name="price" id="edit_price" class="form-control" required>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label">Jumlah</label>
                            <input type="number" name="quantity" id="edit_quantity" class="form-control" required min="1">
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Link Pembelian (Opsional)</label>
                        <input type="url" name="purchase_link" id="edit_purchase_link" class="form-control">
                    </div>

                    <div id="edit_replaced_asset_group">
                        <label class="form-label">Menggantikan Aset Lama (Opsional)</label>
                        <select name="replaced_asset_id" id="edit_replaced_asset_id" class="form-select">
                            <option value="">-- Tidak menggantikan aset apapun --</option>
                            @foreach($assets as $asset)
                                <option value="{{ $asset->id }}">
                                    {{ $asset->name }} [{{ $asset->code ?? 'BELUM DILABEL' }}] (Kondisi: {{ $asset->status }})
                                </option>
                            @endforeach
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
@endif
@endsection

@section('scripts')
<script>
    function toggleReplacedAssetField(prefix) {
        const typeSelect = document.getElementById(prefix + '_type');
        const replacedAssetGroup = document.getElementById(prefix + '_replaced_asset_group');
        if (typeSelect.value === 'bhp') {
            replacedAssetGroup.style.display = 'none';
        } else {
            replacedAssetGroup.style.display = 'block';
        }
    }

    function openCreateModal() {
        toggleReplacedAssetField('create');
        var createModalEl = document.getElementById('createModal');
        var modalInstance = new bootstrap.Modal(createModalEl);
        modalInstance.show();
    }

    function openEditModal(item) {
        document.getElementById('editForm').action = "/kalab/drafts/{{ $draft->id }}/items/" + item.id;
        document.getElementById('edit_type').value = item.type;
        document.getElementById('edit_name').value = item.name;
        document.getElementById('edit_price').value = Math.round(item.price);
        document.getElementById('edit_quantity').value = item.quantity;
        document.getElementById('edit_purchase_link').value = item.purchase_link || '';
        document.getElementById('edit_replaced_asset_id').value = item.replaced_asset_id || '';
        
        toggleReplacedAssetField('edit');
        var editModalEl = document.getElementById('editModal');
        var modalInstance = new bootstrap.Modal(editModalEl);
        modalInstance.show();
    }
</script>
@endsection

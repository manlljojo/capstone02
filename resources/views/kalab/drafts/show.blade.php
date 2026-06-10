@extends('layouts.app')

@section('title', 'Detail Draf Pengadaan')

@section('header_title', 'Draf Pengadaan ' . $draft->year)
@section('header_subtitle', 'Kelola item barang dalam draf pengadaan ini.')

@section('content')
<div class="mb-4">
    <a href="{{ route('kalab.drafts.index') }}" class="btn btn-secondary btn-sm">
        &larr; Kembali ke Draf
    </a>
</div>

<div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-bottom: 24px;">
    <div class="stat-card glass-panel card-info">
        <div class="stat-info">
            <span class="stat-value">Tahun {{ $draft->year }}</span>
            <span class="stat-label">Tahun Anggaran</span>
        </div>
    </div>
    <div class="stat-card glass-panel card-warning">
        <div class="stat-info">
            <span class="stat-value" style="text-transform: uppercase;">{{ str_replace('_', ' ', $draft->status) }}</span>
            <span class="stat-label">Status Draf</span>
        </div>
    </div>
    <div class="stat-card glass-panel card-success">
        <div class="stat-info">
            <span class="stat-value">Rp {{ number_format($items->sum(function($item) { return $item->price * $item->quantity; }), 0, ',', '.') }}</span>
            <span class="stat-label">Estimasi Total Biaya</span>
        </div>
    </div>
</div>

<div class="content-panel glass-panel">
    <div class="panel-header">
        <h3 class="panel-title">Daftar Barang Pengadaan</h3>
        @if($draft->status == 'draft')
            <button class="btn btn-primary btn-sm" onclick="openCreateModal()">Tambah Barang</button>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table">
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
                            <span class="badge {{ $item->type == 'asset' ? 'badge-info' : 'badge-success' }}">
                                {{ $item->type == 'asset' ? 'Aset/Inventaris' : 'BHP' }}
                            </span>
                        </td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        <td>
                            @if($item->purchase_link)
                                <a href="{{ $item->purchase_link }}" target="_blank" class="btn btn-secondary btn-xs" style="color: var(--color-info);">
                                    Buka Link
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($item->replacedAsset)
                                <span style="font-size:0.85rem; color:var(--text-secondary);">
                                    {{ $item->replacedAsset->name }} <br>
                                    <code>({{ $item->replacedAsset->code }})</code>
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($item->status == 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($item->status == 'approved')
                                <span class="badge badge-success">Disetujui</span>
                            @else
                                <span class="badge badge-danger">Ditolak</span>
                            @endif
                        </td>
                        @if($draft->status == 'draft')
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-warning btn-xs" onclick="openEditModal({{ json_encode($item) }})">
                                        Edit
                                    </button>
                                    <form action="{{ route('kalab.drafts.items.destroy', [$draft->id, $item->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $draft->status == 'draft' ? 9 : 8 }}" style="text-align: center; color: var(--text-muted); padding: 32px;">
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
<div id="createModal" class="modal-overlay" style="display: none;">
    <div class="modal-content glass-panel" style="max-width: 600px;">
        <div class="modal-header">
            <h3 class="panel-title">Tambah Barang Pengadaan</h3>
            <button class="modal-close" onclick="closeCreateModal()">&times;</button>
        </div>
        <form action="{{ route('kalab.drafts.items.store', $draft->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Tipe Barang</label>
                <select name="type" id="create_type" class="form-control" onchange="toggleReplacedAssetField('create')" required>
                    <option value="asset">Aset / Inventaris (Komputer, Mikroskop, dll)</option>
                    <option value="bhp">Barang Habis Pakai / BHP (Kabel, Masker, Hand Sanitizer, dll)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Nama Barang</label>
                <input type="text" name="name" class="form-control" required placeholder="Contoh: Mikroskop Olympus BX53">
            </div>

            <div class="grid-2" style="margin-bottom:0;">
                <div class="form-group">
                    <label class="form-label">Harga Estimasi (Satuan)</label>
                    <input type="number" name="price" class="form-control" required placeholder="Contoh: 1500000">
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah</label>
                    <input type="number" name="quantity" class="form-control" required min="1" placeholder="Contoh: 2">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Link Pembelian (Opsional)</label>
                <input type="url" name="purchase_link" class="form-control" placeholder="https://tokopedia.com/...">
            </div>

            <div class="form-group" id="create_replaced_asset_group">
                <label class="form-label">Menggantikan Aset Lama (Opsional)</label>
                <select name="replaced_asset_id" class="form-control">
                    <option value="">-- Tidak menggantikan aset apapun --</option>
                    @foreach($assets as $asset)
                        <option value="{{ $asset->id }}">
                            {{ $asset->name }} [{{ $asset->code ?? 'BELUM DILABEL' }}] (Kondisi: {{ $asset->status }})
                        </option>
                    @endforeach
                </select>
                <p style="color:var(--text-secondary); font-size:0.75rem; margin-top:4px;">
                    *Jika dipilih, aset lama yang digantikan akan diarsipkan ketika barang baru ini diterima oleh staf admin.
                </p>
            </div>

            <div class="d-flex justify-end gap-2 mt-4" style="justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeCreateModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Tambah Barang</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal-overlay" style="display: none;">
    <div class="modal-content glass-panel" style="max-width: 600px;">
        <div class="modal-header">
            <h3 class="panel-title">Ubah Barang Pengadaan</h3>
            <button class="modal-close" onclick="closeEditModal()">&times;</button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Tipe Barang</label>
                <select name="type" id="edit_type" class="form-control" onchange="toggleReplacedAssetField('edit')" required>
                    <option value="asset">Aset / Inventaris</option>
                    <option value="bhp">Barang Habis Pakai / BHP</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Nama Barang</label>
                <input type="text" name="name" id="edit_name" class="form-control" required>
            </div>

            <div class="grid-2" style="margin-bottom:0;">
                <div class="form-group">
                    <label class="form-label">Harga Estimasi (Satuan)</label>
                    <input type="number" name="price" id="edit_price" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah</label>
                    <input type="number" name="quantity" id="edit_quantity" class="form-control" required min="1">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Link Pembelian (Opsional)</label>
                <input type="url" name="purchase_link" id="edit_purchase_link" class="form-control">
            </div>

            <div class="form-group" id="edit_replaced_asset_group">
                <label class="form-label">Menggantikan Aset Lama (Opsional)</label>
                <select name="replaced_asset_id" id="edit_replaced_asset_id" class="form-control">
                    <option value="">-- Tidak menggantikan aset apapun --</option>
                    @foreach($assets as $asset)
                        <option value="{{ $asset->id }}">
                            {{ $asset->name }} [{{ $asset->code ?? 'BELUM DILABEL' }}] (Kondisi: {{ $asset->status }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex justify-end gap-2 mt-4" style="justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
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
        document.getElementById('createModal').style.display = 'flex';
        toggleReplacedAssetField('create');
    }
    
    function closeCreateModal() {
        document.getElementById('createModal').style.display = 'none';
    }

    function openEditModal(item) {
        document.getElementById('editForm').action = "/kalab/drafts/{{ $draft->id }}/items/" + item.id;
        document.getElementById('edit_type').value = item.type;
        document.getElementById('edit_name').value = item.name;
        document.getElementById('edit_price').value = Math.round(item.price);
        document.getElementById('edit_quantity').value = item.quantity;
        document.getElementById('edit_purchase_link').value = item.purchase_link || '';
        document.getElementById('edit_replaced_asset_id').value = item.replaced_asset_id || '';
        
        document.getElementById('editModal').style.display = 'flex';
        toggleReplacedAssetField('edit');
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }
</script>
@endsection

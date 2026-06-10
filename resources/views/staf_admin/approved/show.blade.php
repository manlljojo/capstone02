@extends('layouts.app')

@section('title', 'Catat Penerimaan Barang')

@section('header_title', 'Penerimaan Pengadaan ' . $draft->year)
@section('header_subtitle', 'Catat kedatangan barang secara berkala/parsial.')

@section('content')
<div class="mb-4">
    <a href="{{ route('staf_admin.approved.index') }}" class="btn btn-secondary btn-sm">
        &larr; Kembali ke Daftar
    </a>
</div>

<div class="content-panel glass-panel">
    <div class="panel-header">
        <h3 class="panel-title">Barang Disetujui (Siap Diterima)</h3>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Tipe</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah Disetujui</th>
                    <th>Telah Diterima</th>
                    <th>Belum Datang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    @php
                        $remaining = $item->quantity - $item->received_quantity;
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ $item->name }}</strong>
                            @if($item->replacedAsset)
                                <br>
                                <span style="font-size:0.75rem; color:var(--color-warning);">
                                    Menggantikan: {{ $item->replacedAsset->name }} ({{ $item->replacedAsset->code }})
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $item->type == 'asset' ? 'badge-info' : 'badge-success' }}">
                                {{ $item->type == 'asset' ? 'Aset' : 'BHP' }}
                            </span>
                        </td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>
                            <span class="badge badge-success">{{ $item->received_quantity }}</span>
                        </td>
                        <td>
                            @if($remaining > 0)
                                <span class="badge badge-warning">{{ $remaining }}</span>
                            @else
                                <span class="badge badge-success">Lengkap</span>
                            @endif
                        </td>
                        <td>
                            @if($remaining > 0)
                                <button class="btn btn-primary btn-xs" onclick="openReceiptModal({{ json_encode($item) }})">
                                    Catat Penerimaan
                                </button>
                            @else
                                <span style="color: var(--color-success); font-size: 0.85rem; font-weight:700;">
                                    ✓ Selesai
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Receipt Modal -->
<div id="receiptModal" class="modal-overlay" style="display: none;">
    <div class="modal-content glass-panel">
        <div class="modal-header">
            <h3 class="panel-title">Pencatatan Penerimaan Barang</h3>
            <button class="modal-close" onclick="closeReceiptModal()">&times;</button>
        </div>
        <form id="receiptForm" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Barang</label>
                <input type="text" id="modal_item_name" class="form-control" readonly style="background: rgba(255,255,255,0.05); color: var(--text-secondary);">
            </div>
            
            <div class="form-group">
                <label class="form-label">Tanggal Penerimaan</label>
                <input type="date" name="received_date" class="form-control" required value="{{ date('Y-m-d') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Jumlah yang Datang</label>
                <input type="number" name="quantity_received" id="modal_quantity_received" class="form-control" required min="1" placeholder="Masukkan jumlah barang">
                <p style="color:var(--text-secondary); font-size:0.75rem; margin-top:4px;" id="modal_remaining_info">
                    *Maksimal: 
                </p>
            </div>

            <div class="d-flex justify-end gap-2 mt-4" style="justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeReceiptModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Penerimaan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openReceiptModal(item) {
        const remaining = item.quantity - item.received_quantity;
        document.getElementById('receiptForm').action = "/staf-admin/approved/{{ $draft->id }}/items/" + item.id + "/receipt";
        document.getElementById('modal_item_name').value = item.name;
        document.getElementById('modal_quantity_received').max = remaining;
        document.getElementById('modal_quantity_received').placeholder = "Masukkan jumlah (1 - " + remaining + ")";
        document.getElementById('modal_remaining_info').innerText = "*Jumlah barang yang belum datang: " + remaining + " unit.";
        document.getElementById('receiptModal').style.display = 'flex';
    }

    function closeReceiptModal() {
        document.getElementById('receiptModal').style.display = 'none';
    }
</script>
@endsection

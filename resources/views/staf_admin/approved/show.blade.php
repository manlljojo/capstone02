@extends('layouts.app')

@section('title', 'Catat Penerimaan Barang')

@section('header_title', 'Penerimaan Pengadaan ' . $draft->year)

@section('content')
<div class="mb-4">
    <a href="{{ route('staf_admin.approved.index') }}" class="btn btn-white btn-sm d-inline-flex align-items-center gap-1">
        <i class="ti ti-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

<div class="card card-lg shadow-sm">
    <div class="card-header border-bottom-0">
        <h5 class="mb-0">Barang Disetujui (Siap Diterima)</h5>
    </div>

    <div class="table-responsive">
        <table class="table text-nowrap mb-0 table-centered table-hover">
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
                                <span class="small text-warning">
                                    Menggantikan: {{ $item->replacedAsset->name }} ({{ $item->replacedAsset->code }})
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($item->type == 'asset')
                                <span class="badge text-info-emphasis bg-info-subtle">Aset</span>
                            @else
                                <span class="badge text-success-emphasis bg-success-subtle">BHP</span>
                            @endif
                        </td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>
                            <span class="badge text-success-emphasis bg-success-subtle">{{ $item->received_quantity }}</span>
                        </td>
                        <td>
                            @if($remaining > 0)
                                <span class="badge text-warning-emphasis bg-warning-subtle">{{ $remaining }}</span>
                            @else
                                <span class="badge text-success-emphasis bg-success-subtle">Lengkap</span>
                            @endif
                        </td>
                        <td>
                            @if($remaining > 0)
                                <button class="btn btn-primary btn-sm" onclick="openReceiptModal({{ json_encode($item) }})">
                                    Catat Penerimaan
                                </button>
                            @else
                                <span class="text-success small fw-bold">
                                    <i class="ti ti-check"></i> Selesai
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
<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="receiptModalLabel">Pencatatan Penerimaan Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="receiptForm" method="POST">
                @csrf
                <div class="modal-body d-flex flex-column gap-3">
                    <div>
                        <label class="form-label">Nama Barang</label>
                        <input type="text" id="modal_item_name" class="form-control bg-light" readonly>
                    </div>
                    
                    <div>
                        <label class="form-label">Tanggal Penerimaan</label>
                        <input type="date" name="received_date" class="form-control" required value="{{ date('Y-m-d') }}">
                    </div>

                    <div>
                        <label class="form-label">Jumlah yang Datang</label>
                        <input type="number" name="quantity_received" id="modal_quantity_received" class="form-control" required min="1" placeholder="Masukkan jumlah barang">
                        <p class="text-secondary small mt-1" id="modal_remaining_info">
                            *Maksimal: 
                        </p>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Penerimaan</button>
                </div>
            </form>
        </div>
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
        
        var receiptModalEl = document.getElementById('receiptModal');
        var modalInstance = new bootstrap.Modal(receiptModalEl);
        modalInstance.show();
    }
</script>
@endsection

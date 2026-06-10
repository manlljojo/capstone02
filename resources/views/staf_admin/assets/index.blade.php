@extends('layouts.app')

@section('title', 'Pelabelan Aset')

@section('header_title', 'Registrasi & Pelabelan Aset')
@section('header_subtitle', 'Masukkan nomor label, alokasikan ke ruangan, dan upload barcode/QR untuk aset laboratorium.')

@section('content')
<div class="content-panel glass-panel">
    <div class="panel-header">
        <h3 class="panel-title">Daftar Inventaris Aset</h3>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Foto QR/Barcode</th>
                    <th>Nama Aset</th>
                    <th>Kode Label</th>
                    <th>Ruangan</th>
                    <th>Status Kondisi</th>
                    <th>Tanggal Masuk</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assets as $a)
                    <tr>
                        <td>
                            @if($a->barcode_photo)
                                <img src="{{ asset($a->barcode_photo) }}" alt="QR Barcode" class="asset-thumbnail" onclick="viewBarcode('{{ asset($a->barcode_photo) }}', '{{ $a->name }}')" style="cursor: pointer;" title="Klik untuk perbesar">
                            @else
                                <div class="asset-thumbnail" style="display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,0.03);color:var(--text-muted);font-size:0.75rem;text-align:center;">
                                    Belum ada foto
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $a->name }}</strong>
                            <br>
                            <span style="font-size:0.8rem; color:var(--text-secondary);">Rp {{ number_format($a->price, 0, ',', '.') }}</span>
                        </td>
                        <td>
                            @if($a->code)
                                <code>{{ $a->code }}</code>
                            @else
                                <span style="color:var(--color-danger);font-size:0.85rem;font-weight:700;">BELUM DI-SET</span>
                            @endif
                        </td>
                        <td>
                            @if($a->room)
                                <strong>{{ $a->room->name }}</strong> <br>
                                <code style="font-size:0.75rem;">({{ $a->room->code }})</code>
                            @else
                                <span style="color:var(--text-muted);font-size:0.85rem;">Belum Dialokasikan</span>
                            @endif
                        </td>
                        <td>
                            @if($a->status == 'baik')
                                <span class="badge badge-success">Baik</span>
                            @elseif($a->status == 'rusak')
                                <span class="badge badge-danger">Rusak</span>
                            @elseif($a->status == 'maintenance')
                                <span class="badge badge-warning">Maintenance</span>
                            @else
                                <span class="badge badge-info">Diarsipkan</span>
                            @endif
                        </td>
                        <td>{{ $a->purchase_date ? \Carbon\Carbon::parse($a->purchase_date)->format('d M Y') : '-' }}</td>
                        <td>
                            <button class="btn btn-warning btn-xs" onclick="openLabelModal({{ json_encode($a) }})">
                                {{ ($a->code && $a->room_id) ? 'Ubah Label' : 'Beri Label' }}
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Label/Allocation Modal -->
<div id="labelModal" class="modal-overlay" style="display: none;">
    <div class="modal-content glass-panel">
        <div class="modal-header">
            <h3 class="panel-title" id="modal_title">Pelabelan Aset</h3>
            <button class="modal-close" onclick="closeLabelModal()">&times;</button>
        </div>
        <form id="labelForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label class="form-label">Nama Aset</label>
                <input type="text" id="label_asset_name" class="form-control" readonly style="background: rgba(255,255,255,0.05); color: var(--text-secondary);">
            </div>

            <div class="form-group">
                <label class="form-label">Alokasikan ke Ruangan</label>
                <select name="room_id" id="label_room_id" class="form-control" required>
                    <option value="">-- Pilih Ruangan --</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}">{{ $room->name }} ({{ $room->code }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Nomor Label / Kode Aset (Unik)</label>
                <input type="text" name="code" id="label_code" class="form-control" required placeholder="Contoh: BIO-MIK-005">
            </div>

            <div class="form-group">
                <label class="form-label">Upload Foto QR / Barcode</label>
                <div class="file-upload-wrapper">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" class="upload-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p style="font-size:0.85rem; color:var(--text-secondary); margin-bottom:4px;">
                        Klik atau seret file gambar ke sini
                    </p>
                    <p style="font-size:0.75rem; color:var(--text-muted);">
                        PNG, JPG, JPEG (Max 2MB)
                    </p>
                    <input type="file" name="barcode_photo" class="file-upload-input" accept="image/*">
                </div>
            </div>

            <div class="d-flex justify-end gap-2 mt-4" style="justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeLabelModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Registrasi</button>
            </div>
        </form>
    </div>
</div>

<!-- Image Viewer Modal -->
<div id="imageModal" class="modal-overlay" style="display: none;" onclick="closeImageViewer()">
    <div class="modal-content glass-panel" style="max-width: 360px; text-align: center;" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 class="panel-title" id="image_modal_title">Foto QR/Barcode</h3>
            <button class="modal-close" onclick="closeImageViewer()">&times;</button>
        </div>
        <img id="image_modal_src" src="" alt="Barcode Besar" class="barcode-preview-modal" style="width:100%; height:auto;">
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openLabelModal(asset) {
        document.getElementById('labelForm').action = "/staf-admin/assets/" + asset.id;
        document.getElementById('label_asset_name').value = asset.name;
        document.getElementById('label_room_id').value = asset.room_id || '';
        document.getElementById('label_code').value = asset.code || '';
        
        if (asset.code) {
            document.getElementById('modal_title').innerText = "Ubah Label Aset";
        } else {
            document.getElementById('modal_title').innerText = "Beri Label Aset Baru";
        }

        document.getElementById('labelModal').style.display = 'flex';
    }

    function closeLabelModal() {
        document.getElementById('labelModal').style.display = 'none';
    }

    function viewBarcode(src, name) {
        document.getElementById('image_modal_src').src = src;
        document.getElementById('image_modal_title').innerText = "QR/Barcode: " + name;
        document.getElementById('imageModal').style.display = 'flex';
    }

    function closeImageViewer() {
        document.getElementById('imageModal').style.display = 'none';
    }
</script>
@endsection

@extends('layouts.app')

@section('title', 'Pelabelan Aset')

@section('header_title', 'Registrasi & Pelabelan Aset')

@section('content')
<div class="card card-lg shadow-sm">
    <div class="card-header border-bottom-0">
        <h5 class="mb-0">Daftar Inventaris Aset</h5>
    </div>

    <div class="table-responsive">
        <table class="table text-nowrap mb-0 table-centered table-hover">
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
                                <div class="asset-thumbnail bg-light text-muted d-flex align-items-center justify-content-center border" style="font-size:0.75rem; text-align:center;">
                                    Belum ada foto
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $a->name }}</strong>
                            <br>
                            <span class="small text-secondary">Rp {{ number_format($a->price, 0, ',', '.') }}</span>
                        </td>
                        <td>
                            @if($a->code)
                                <div class="univ-asset-tag">
                                    <div class="univ-tag-header">UK MARANATHA</div>
                                    <div class="univ-tag-code">{{ $a->code }}</div>
                                    <div class="univ-tag-footer">INVENTARIS LAB</div>
                                </div>
                            @else
                                <span class="badge text-danger-emphasis bg-danger-subtle px-3 py-2">BELUM DILABEL</span>
                            @endif
                        </td>
                        <td>
                            @if($a->room)
                                <strong>{{ $a->room->name }}</strong> <br>
                                <code style="font-size:0.75rem;">({{ $a->room->code }})</code>
                            @else
                                <span class="small text-secondary">Belum Dialokasikan</span>
                            @endif
                        </td>
                        <td>
                            @if($a->status == 'baik')
                                <span class="badge text-success-emphasis bg-success-subtle">Baik</span>
                            @elseif($a->status == 'rusak')
                                <span class="badge text-danger-emphasis bg-danger-subtle">Rusak</span>
                            @elseif($a->status == 'maintenance')
                                <span class="badge text-warning-emphasis bg-warning-subtle">Maintenance</span>
                            @else
                                <span class="badge text-secondary-emphasis bg-secondary-subtle">Diarsipkan</span>
                            @endif
                        </td>
                        <td>{{ $a->purchase_date ? \Carbon\Carbon::parse($a->purchase_date)->format('d M Y') : '-' }}</td>
                        <td>
                            <button class="btn btn-white btn-sm" onclick="openLabelModal({{ json_encode($a) }})">
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
<div class="modal fade" id="labelModal" tabindex="-1" aria-labelledby="labelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="modal_title">Pelabelan Aset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="labelForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body d-flex flex-column gap-3">
                    <div>
                        <label class="form-label">Nama Aset</label>
                        <input type="text" id="label_asset_name" class="form-control bg-light" readonly>
                    </div>

                    <div>
                        <label class="form-label">Alokasikan ke Ruangan</label>
                        <select name="room_id" id="label_room_id" class="form-select" required>
                            <option value="">-- Pilih Ruangan --</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->name }} ({{ $room->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Nomor Label / Kode Aset (Unik)</label>
                        <input type="text" name="code" id="label_code" class="form-control" required placeholder="Contoh: BIO-MIK-005">
                    </div>

                    <div>
                        <label class="form-label">Upload Foto QR / Barcode</label>
                        <div class="file-upload-wrapper border border-dashed rounded p-4 text-center position-relative bg-light-subtle">
                            <i class="ti ti-camera fs-3 text-secondary mb-2 d-block"></i>
                            <p class="small text-secondary mb-1">
                                Klik atau seret file gambar ke sini
                            </p>
                            <p class="small text-muted mb-0" style="font-size: 0.7rem;">
                                PNG, JPG, JPEG (Max 2MB)
                            </p>
                            <input type="file" name="barcode_photo" class="file-upload-input position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Registrasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Image Viewer Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="image_modal_title">Foto QR/Barcode</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="image_modal_src" src="" alt="Barcode Besar" class="img-fluid rounded border border-light-subtle" style="max-height: 300px; object-fit: contain;">
            </div>
        </div>
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

        var labelModalEl = document.getElementById('labelModal');
        var modalInstance = new bootstrap.Modal(labelModalEl);
        modalInstance.show();
    }

    function viewBarcode(src, name) {
        document.getElementById('image_modal_src').src = src;
        document.getElementById('image_modal_title').innerText = "QR/Barcode: " + name;
        
        var imageModalEl = document.getElementById('imageModal');
        var modalInstance = new bootstrap.Modal(imageModalEl);
        modalInstance.show();
    }
</script>
@endsection

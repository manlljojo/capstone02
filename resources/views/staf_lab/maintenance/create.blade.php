@extends('layouts.app')

@section('title', 'Catat Maintenance')

@section('header_title', 'Catat Pemeliharaan Aset')

@section('content')
<div class="mb-4">
    <a href="{{ route('staf_lab.maintenance.index') }}" class="btn btn-white btn-sm d-inline-flex align-items-center gap-1">
        <i class="ti ti-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

<div class="card card-lg shadow-sm" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header border-bottom-0">
        <h5 class="mb-0">Form Pemeliharaan Aset</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('staf_lab.maintenance.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Pilih Aset / Inventaris</label>
                <select name="asset_id" id="asset_select" class="form-select" required onchange="updateCurrentStatus()">
                    <option value="">-- Pilih Aset --</option>
                    @foreach($assets as $asset)
                        <option value="{{ $asset->id }}" data-status="{{ $asset->status }}">
                            {{ $asset->name }} [{{ $asset->code ?? 'BELUM DILABEL' }}] (Kondisi saat ini: {{ $asset->status }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6 col-12">
                    <label class="form-label">Tanggal Pemeliharaan</label>
                    <input type="date" name="maintenance_date" class="form-control" required value="{{ date('Y-m-d') }}">
                </div>

                <div class="col-md-6 col-12">
                    <label class="form-label">Kondisi Setelah Maintenance</label>
                    <select name="status_after" class="form-select" required>
                        <option value="baik">Baik / Normal</option>
                        <option value="rusak">Rusak</option>
                        <option value="maintenance">Dalam Pemeliharaan (Maintenance)</option>
                        <option value="diarsipkan">Diarsipkan / Dihapus (Penghapusan Aset)</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi Pekerjaan Pemeliharaan</label>
                <textarea name="description" class="form-control" rows="4" required placeholder="Jelaskan detail perbaikan, misal: 'Membersihkan lensa mikroskop, merapikan kabel UTP di server, dll.'"></textarea>
            </div>

            <!-- BHP Usage Section -->
            <div class="mt-4 pt-4 border-top">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 fw-bold">BHP yang Digunakan (Opsional)</h6>
                    <button type="button" class="btn btn-white btn-sm d-inline-flex align-items-center gap-1" onclick="addBhpRow()">
                        <i class="ti ti-plus"></i> Tambah BHP
                    </button>
                </div>
                
                <div id="bhp_usages_container" class="d-flex flex-column gap-2 mb-3">
                    <!-- Rows injected dynamically via JS -->
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                <a href="{{ route('staf_lab.maintenance.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Log Pemeliharaan</button>
            </div>
        </form>
    </div>
</div>

<!-- BHP Template Data for JS -->
<div id="bhp_options_data" style="display:none;">
    @foreach($bhps as $bhp)
        <option value="{{ $bhp->id }}" data-stock="{{ $bhp->stock }}" data-unit="{{ $bhp->unit }}">{{ $bhp->name }} (Stok: {{ $bhp->stock }} {{ $bhp->unit }})</option>
    @endforeach
</div>
@endsection

@section('scripts')
<script>
    let bhpIndex = 0;

    function addBhpRow() {
        const container = document.getElementById('bhp_usages_container');
        const optionsHtml = document.getElementById('bhp_options_data').innerHTML;

        if (!optionsHtml.trim()) {
            alert('Tidak ada stok BHP tersedia untuk digunakan.');
            return;
        }

        const row = document.createElement('div');
        row.className = 'row g-3 align-items-center mb-2';
        row.id = `bhp_row_${bhpIndex}`;
        row.innerHTML = `
            <div class="col-md-6 col-12">
                <select name="bhp_usages[${bhpIndex}][bhp_id]" class="form-select" required onchange="handleBhpChange(${bhpIndex})">
                    <option value="">-- Pilih BHP --</option>
                    ${optionsHtml}
                </select>
            </div>
            <div class="col-md-2 col-4">
                <input type="number" name="bhp_usages[${bhpIndex}][quantity]" id="bhp_qty_${bhpIndex}" class="form-control" required min="1" placeholder="Qty">
            </div>
            <div class="col-md-2 col-4 text-secondary small" id="bhp_unit_${bhpIndex}">
                -
            </div>
            <div class="col-md-2 col-4 text-end">
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeBhpRow(${bhpIndex})">
                    Hapus
                </button>
            </div>
        `;
        container.appendChild(row);
        bhpIndex++;
    }

    function removeBhpRow(index) {
        const row = document.getElementById(`bhp_row_${index}`);
        row.remove();
    }

    function handleBhpChange(index) {
        const select = document.querySelector(`select[name="bhp_usages[index][bhp_id]"]`.replace('index', index));
        const qtyInput = document.getElementById(`bhp_qty_${index}`);
        const unitLabel = document.getElementById(`bhp_unit_${index}`);
        
        if (select.value === "") {
            qtyInput.max = "";
            unitLabel.innerText = "-";
            return;
        }

        const selectedOption = select.options[select.selectedIndex];
        const stock = selectedOption.getAttribute('data-stock');
        const unit = selectedOption.getAttribute('data-unit');

        qtyInput.max = stock;
        qtyInput.placeholder = `Maks ${stock}`;
        unitLabel.innerText = unit;
    }

    function updateCurrentStatus() {
        const select = document.getElementById('asset_select');
        if (select.value === "") return;
        
        const selectedOption = select.options[select.selectedIndex];
        const currentStatus = selectedOption.getAttribute('data-status');
        
        const statusAfterSelect = document.querySelector('select[name="status_after"]');
        statusAfterSelect.value = currentStatus;
    }
</script>
@endsection

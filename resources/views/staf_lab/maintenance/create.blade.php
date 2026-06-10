@extends('layouts.app')

@section('title', 'Catat Maintenance')

@section('header_title', 'Catat Pemeliharaan Aset')
@section('header_subtitle', 'Masukkan detail perbaikan aset dan penggunaan BHP.')

@section('content')
<div class="mb-4">
    <a href="{{ route('staf_lab.maintenance.index') }}" class="btn btn-secondary btn-sm">
        &larr; Kembali ke Daftar
    </a>
</div>

<div class="content-panel glass-panel" style="max-width: 800px; margin: 0 auto;">
    <div class="panel-header">
        <h3 class="panel-title">Form Pemeliharaan Aset</h3>
    </div>

    <form action="{{ route('staf_lab.maintenance.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label class="form-label">Pilih Aset / Inventaris</label>
            <select name="asset_id" id="asset_select" class="form-control" required onchange="updateCurrentStatus()">
                <option value="">-- Pilih Aset --</option>
                @foreach($assets as $asset)
                    <option value="{{ $asset->id }}" data-status="{{ $asset->status }}">
                        {{ $asset->name }} [{{ $asset->code ?? 'BELUM DILABEL' }}] (Kondisi saat ini: {{ $asset->status }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid-2" style="margin-bottom:0;">
            <div class="form-group">
                <label class="form-label">Tanggal Pemeliharaan</label>
                <input type="date" name="maintenance_date" class="form-control" required value="{{ date('Y-m-d') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Kondisi Setelah Maintenance</label>
                <select name="status_after" class="form-control" required>
                    <option value="baik">Baik / Normal</option>
                    <option value="rusak">Rusak</option>
                    <option value="maintenance">Dalam Pemeliharaan (Maintenance)</option>
                    <option value="diarsipkan">Diarsipkan / Dihapus (Penghapusan Aset)</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Deskripsi Pekerjaan Pemeliharaan</label>
            <textarea name="description" class="form-control" rows="4" required placeholder="Jelaskan detail perbaikan, misal: 'Membersihkan lensa mikroskop, merapikan kabel UTP di server, dll.'"></textarea>
        </div>

        <!-- BHP Usage Section -->
        <div style="margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--border-glass);">
            <div class="panel-header" style="margin-bottom: 16px;">
                <h4 style="color:#ffffff; font-size:1.05rem;">BHP yang Digunakan (Opsional)</h4>
                <button type="button" class="btn btn-secondary btn-xs" onclick="addBhpRow()">
                    + Tambah BHP
                </button>
            </div>
            
            <div id="bhp_usages_container" style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px;">
                <!-- Rows injected dynamically via JS -->
            </div>
        </div>

        <div class="d-flex justify-end gap-2 mt-4" style="justify-content: flex-end; padding-top: 16px; border-top: 1px solid var(--border-glass);">
            <a href="{{ route('staf_lab.maintenance.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Log Pemeliharaan</button>
        </div>
    </form>
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
        row.className = 'd-flex gap-3 align-center';
        row.id = `bhp_row_${bhpIndex}`;
        row.innerHTML = `
            <div style="flex-grow: 2;">
                <select name="bhp_usages[${bhpIndex}][bhp_id]" class="form-control" required onchange="handleBhpChange(${bhpIndex})">
                    <option value="">-- Pilih BHP --</option>
                    ${optionsHtml}
                </select>
            </div>
            <div style="width: 120px;">
                <input type="number" name="bhp_usages[${bhpIndex}][quantity]" id="bhp_qty_${bhpIndex}" class="form-control" required min="1" placeholder="Qty">
            </div>
            <div style="width: 80px; color: var(--text-secondary); font-size: 0.85rem;" id="bhp_unit_${bhpIndex}">
                -
            </div>
            <div>
                <button type="button" class="btn btn-danger btn-xs" onclick="removeBhpRow(${bhpIndex})">
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
        const select = document.querySelector(`select[name="bhp_usages[${index}][bhp_id]"]`);
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
        
        // Optionally set status_after to current status as default
        const statusAfterSelect = document.querySelector('select[name="status_after"]');
        statusAfterSelect.value = currentStatus;
    }
</script>
@endsection

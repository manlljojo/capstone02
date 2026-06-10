@extends('layouts.app')

@section('title', 'Draf Pengadaan')

@section('header_title', 'Pengadaan Aset & BHP')
@section('header_subtitle', 'Kelola draf pengadaan tahunan untuk aset laboratorium dan barang habis pakai (BHP).')

@section('content')
<div class="grid-2">
    <!-- Active Drafts List -->
    <div class="content-panel glass-panel" style="grid-column: span 2;">
        <div class="panel-header">
            <h3 class="panel-title">Draf Pengadaan Aktif</h3>
            <button class="btn btn-primary btn-sm" onclick="openCreateModal()">Buat Draf Baru</button>
        </div>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tahun Anggaran</th>
                        <th>Status</th>
                        <th>Jumlah Barang</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($drafts as $d)
                        <tr>
                            <td><strong>Tahun {{ $d->year }}</strong></td>
                            <td>
                                @if($d->status == 'draft')
                                    <span class="badge badge-warning">Draft (Editable)</span>
                                @elseif($d->status == 'pending_review')
                                    <span class="badge badge-info">Pending Review (Locked)</span>
                                @endif
                            </td>
                            <td>{{ $d->items()->count() }} barang</td>
                            <td>{{ $d->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('kalab.drafts.show', $d->id) }}" class="btn btn-secondary btn-xs">
                                        {{ $d->status == 'draft' ? 'Edit Items' : 'Lihat Items' }}
                                    </a>
                                    @if($d->status == 'draft')
                                        <form action="{{ route('kalab.drafts.submit', $d->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengajukan draf pengadaan ini ke Kaprodi? Data tidak dapat diubah setelah diajukan.')">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-xs">Ajukan ke Kaprodi</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 32px;">
                                Tidak ada draf pengadaan aktif. Silakan buat draf baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div id="createModal" class="modal-overlay" style="display: none;">
    <div class="modal-content glass-panel">
        <div class="modal-header">
            <h3 class="panel-title">Buat Draf Pengadaan Baru</h3>
            <button class="modal-close" onclick="closeCreateModal()">&times;</button>
        </div>
        <form action="{{ route('kalab.drafts.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Tahun Anggaran</label>
                <select name="year" class="form-control" required>
                    @for($y = date('Y'); $y <= date('Y') + 5; $y++)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="d-flex justify-end gap-2 mt-4" style="justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeCreateModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Buat Draf</button>
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
</script>
@endsection

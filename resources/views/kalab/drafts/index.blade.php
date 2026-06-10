@extends('layouts.app')

@section('title', 'Draf Pengadaan')

@section('header_title', 'Pengadaan Aset & BHP')

@section('content')
<div class="card card-lg shadow-sm">
    <div class="card-header border-bottom-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0">Draf Pengadaan Aktif</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">Buat Draf Baru</button>
    </div>
    
    <div class="table-responsive">
        <table class="table text-nowrap mb-0 table-centered table-hover">
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
                                <span class="badge text-warning-emphasis bg-warning-subtle">Draft (Editable)</span>
                            @elseif($d->status == 'pending_review')
                                <span class="badge text-info-emphasis bg-info-subtle">Pending Review (Locked)</span>
                            @endif
                        </td>
                        <td>{{ $d->items()->count() }} barang</td>
                        <td>{{ $d->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('kalab.drafts.show', $d->id) }}" class="btn btn-white btn-sm">
                                    {{ $d->status == 'draft' ? 'Edit Items' : 'Lihat Items' }}
                                </a>
                                @if($d->status == 'draft')
                                    <form action="{{ route('kalab.drafts.submit', $d->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengajukan draf pengadaan ini ke Kaprodi? Data tidak dapat diubah setelah diajukan.')">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm text-white">Ajukan ke Kaprodi</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-secondary py-4">
                            Tidak ada draf pengadaan aktif. Silakan buat draf baru.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="createModalLabel">Buat Draf Pengadaan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('kalab.drafts.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tahun Anggaran</label>
                        <select name="year" class="form-select" required>
                            @for($y = date('Y'); $y <= date('Y') + 5; $y++)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Buat Draf</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

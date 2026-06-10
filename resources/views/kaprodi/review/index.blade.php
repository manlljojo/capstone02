@extends('layouts.app')

@section('title', 'Tinjau Pengadaan')

@section('header_title', 'Review Pengadaan Barang')
@section('header_subtitle', 'Tinjau, setujui, atau tolak draf pengadaan tahunan dari Kepala Laboratorium.')

@section('content')
<div class="grid-2">
    <!-- Pending Review Section -->
    <div class="content-panel glass-panel" style="grid-column: span 2;">
        <div class="panel-header">
            <h3 class="panel-title">Menunggu Tinjauan</h3>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tahun Anggaran</th>
                        <th>Diusulkan Oleh</th>
                        <th>Jumlah Item</th>
                        <th>Tanggal Masuk</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pending_drafts as $d)
                        <tr>
                            <td><strong>Tahun {{ $d->year }}</strong></td>
                            <td>{{ $d->creator->name }}</td>
                            <td>{{ $d->items()->count() }} barang</td>
                            <td>{{ $d->updated_at->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('kaprodi.review.show', $d->id) }}" class="btn btn-warning btn-xs">
                                    Mulai Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 32px;">
                                Tidak ada pengajuan pengadaan yang perlu direview saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Finalized Drafts Section -->
    <div class="content-panel glass-panel" style="grid-column: span 2;">
        <div class="panel-header">
            <h3 class="panel-title">Riwayat Finalisasi</h3>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tahun Anggaran</th>
                        <th>Diusulkan Oleh</th>
                        <th>Status</th>
                        <th>Tanggal Finalisasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($finalized_drafts as $d)
                        <tr>
                            <td><strong>Tahun {{ $d->year }}</strong></td>
                            <td>{{ $d->creator->name }}</td>
                            <td>
                                <span class="badge badge-success">Finalized & Locked</span>
                            </td>
                            <td>{{ $d->updated_at->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('kaprodi.review.show', $d->id) }}" class="btn btn-secondary btn-xs">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 32px;">
                                Belum ada pengadaan yang difinalisasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

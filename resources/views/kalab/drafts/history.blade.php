@extends('layouts.app')

@section('title', 'Riwayat Pengadaan')

@section('header_title', 'Riwayat Pengadaan')
@section('header_subtitle', 'Arsip daftar pengadaan tahunan yang telah disetujui dan difinalisasi.')

@section('content')
<div class="content-panel glass-panel">
    <div class="panel-header">
        <h3 class="panel-title">Pengadaan yang Telah Final</h3>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Tahun Anggaran</th>
                    <th>Status</th>
                    <th>Jumlah Barang</th>
                    <th>Tanggal Finalisasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($drafts as $d)
                    <tr>
                        <td><strong>Tahun {{ $d->year }}</strong></td>
                        <td>
                            <span class="badge badge-success">Finalized & Locked</span>
                        </td>
                        <td>{{ $d->items()->count() }} barang</td>
                        <td>{{ $d->updated_at->format('d M Y H:i') }}</td>
                        <td>
                            <a href="{{ route('kalab.drafts.show', $d->id) }}" class="btn btn-secondary btn-xs">
                                Lihat Rincian Barang
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 32px;">
                            Belum ada riwayat pengadaan yang difinalisasi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

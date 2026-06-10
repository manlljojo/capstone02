@extends('layouts.app')

@section('title', 'Riwayat Pengadaan')

@section('header_title', 'Riwayat Pengadaan')

@section('content')
<div class="card card-lg shadow-sm">
    <div class="card-header border-bottom-0">
        <h5 class="mb-0">Pengadaan yang Telah Final</h5>
    </div>
    
    <div class="table-responsive">
        <table class="table text-nowrap mb-0 table-centered table-hover">
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
                            <span class="badge text-success-emphasis bg-success-subtle">Finalized & Locked</span>
                        </td>
                        <td>{{ $d->items()->count() }} barang</td>
                        <td>{{ $d->updated_at->format('d M Y H:i') }}</td>
                        <td>
                            <a href="{{ route('kalab.drafts.show', $d->id) }}" class="btn btn-white btn-sm">
                                Lihat Rincian Barang
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-secondary py-4">
                            Belum ada riwayat pengadaan yang difinalisasi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

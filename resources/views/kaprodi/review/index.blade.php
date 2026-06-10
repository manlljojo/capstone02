@extends('layouts.app')

@section('title', 'Tinjau Pengadaan')

@section('header_title', 'Review Pengadaan Barang')

@section('content')
<div class="row g-6">
    <!-- Pending Review Section -->
    <div class="col-12">
        <div class="card card-lg shadow-sm">
            <div class="card-header border-bottom-0">
                <h5 class="mb-0">Menunggu Tinjauan</h5>
            </div>
            <div class="table-responsive">
                <table class="table text-nowrap mb-0 table-centered table-hover">
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
                                    <a href="{{ route('kaprodi.review.show', $d->id) }}" class="btn btn-warning btn-sm text-white">
                                        Mulai Review
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-secondary py-4">
                                    Tidak ada pengajuan pengadaan yang perlu direview saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Finalized Drafts Section -->
    <div class="col-12">
        <div class="card card-lg shadow-sm mt-4">
            <div class="card-header border-bottom-0">
                <h5 class="mb-0">Riwayat Finalisasi</h5>
            </div>
            <div class="table-responsive">
                <table class="table text-nowrap mb-0 table-centered table-hover">
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
                                    <span class="badge text-success-emphasis bg-success-subtle">Finalized & Locked</span>
                                </td>
                                <td>{{ $d->updated_at->format('d M Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('kaprodi.review.show', $d->id) }}" class="btn btn-white btn-sm">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-secondary py-4">
                                    Belum ada pengadaan yang difinalisasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

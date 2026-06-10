@extends('layouts.app')

@section('title', 'Penerimaan Barang')

@section('header_title', 'Penerimaan Barang')

@section('content')
<div class="card card-lg shadow-sm">
    <div class="card-header border-bottom-0">
        <h5 class="mb-0">Daftar Pengadaan Disetujui</h5>
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
                @forelse($drafts as $d)
                    <tr>
                        <td><strong>Tahun {{ $d->year }}</strong></td>
                        <td>{{ $d->creator->name }}</td>
                        <td>
                            <span class="badge text-success-emphasis bg-success-subtle">Approved & Locked</span>
                        </td>
                        <td>{{ $d->updated_at->format('d M Y H:i') }}</td>
                        <td>
                            <a href="{{ route('staf_admin.approved.show', $d->id) }}" class="btn btn-primary btn-sm">
                                Catat Penerimaan
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-secondary py-4">
                            Belum ada draf pengadaan yang disetujui (status final) untuk diproses.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

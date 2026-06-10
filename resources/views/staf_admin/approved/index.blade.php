@extends('layouts.app')

@section('title', 'Penerimaan Barang')

@section('header_title', 'Penerimaan Barang')
@section('header_subtitle', 'Pencatatan barang masuk dari draf pengadaan yang telah disetujui.')

@section('content')
<div class="content-panel glass-panel">
    <div class="panel-header">
        <h3 class="panel-title">Daftar Pengadaan Disetujui</h3>
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
                @forelse($drafts as $d)
                    <tr>
                        <td><strong>Tahun {{ $d->year }}</strong></td>
                        <td>{{ $d->creator->name }}</td>
                        <td>
                            <span class="badge badge-success">Approved & Locked</span>
                        </td>
                        <td>{{ $d->updated_at->format('d M Y H:i') }}</td>
                        <td>
                            <a href="{{ route('staf_admin.approved.show', $d->id) }}" class="btn btn-primary btn-xs">
                                Catat Penerimaan
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 32px;">
                            Belum ada draf pengadaan yang disetujui (status final) untuk diproses.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

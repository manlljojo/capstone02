@extends('layouts.app')

@section('title', 'Pemeliharaan Aset')

@section('header_title', 'Riwayat Pemeliharaan Aset')
@section('header_subtitle', 'Catatan pemeliharaan preventif dan perbaikan aset laboratorium.')

@section('content')
<div class="content-panel glass-panel">
    <div class="panel-header">
        <h3 class="panel-title">Log Pemeliharaan</h3>
        <a href="{{ route('staf_lab.maintenance.create') }}" class="btn btn-primary btn-sm">Catat Maintenance Baru</a>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Aset</th>
                    <th>Kode Label</th>
                    <th>Tanggal</th>
                    <th>Deskripsi Pemeliharaan</th>
                    <th>Kondisi (Sebelum &rarr; Sesudah)</th>
                    <th>Penggunaan BHP</th>
                    <th>Petugas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td><strong>{{ $log->asset->name }}</strong></td>
                        <td><code>{{ $log->asset->code ?? 'BELUM DILABEL' }}</code></td>
                        <td>{{ \Carbon\Carbon::parse($log->maintenance_date)->format('d M Y') }}</td>
                        <td style="max-width:300px; word-wrap:break-word;">
                            {{ $log->description }}
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $log->status_before }}</span>
                            <span style="color:var(--text-secondary)">→</span>
                            <span class="badge badge-success">{{ $log->status_after }}</span>
                        </td>
                        <td>
                            @forelse($log->bhpUsages as $usage)
                                <span style="font-size:0.85rem; display:block; color:var(--text-secondary);">
                                    • {{ $usage->bhp->name }} <strong>({{ $usage->quantity_used }} {{ $usage->bhp->unit }})</strong>
                                </span>
                            @empty
                                <span style="color:var(--text-muted); font-size:0.85rem;">Tidak menggunakan BHP</span>
                            @endforelse
                        </td>
                        <td>{{ $log->creator->name }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 32px;">
                            Belum ada riwayat pemeliharaan terdaftar. Silakan catat pemeliharaan baru.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

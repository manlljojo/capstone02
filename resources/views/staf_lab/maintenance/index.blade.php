@extends('layouts.app')

@section('title', 'Pemeliharaan Aset')

@section('header_title', 'Riwayat Pemeliharaan Aset')

@section('content')
<div class="card card-lg shadow-sm">
    <div class="card-header border-bottom-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0">Log Pemeliharaan</h5>
        <a href="{{ route('staf_lab.maintenance.create') }}" class="btn btn-primary btn-sm">Catat Maintenance Baru</a>
    </div>

    <div class="table-responsive">
        <table class="table text-nowrap mb-0 table-centered table-hover">
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
                        <td style="max-width:300px; white-space: normal; word-wrap: break-word;">
                            {{ $log->description }}
                        </td>
                        <td>
                            <span class="badge text-info-emphasis bg-info-subtle">{{ $log->status_before }}</span>
                            <span class="text-muted px-1">→</span>
                            <span class="badge text-success-emphasis bg-success-subtle">{{ $log->status_after }}</span>
                        </td>
                        <td>
                            @forelse($log->bhpUsages as $usage)
                                <span class="small text-secondary d-block">
                                    • {{ $usage->bhp->name }} <strong>({{ $usage->quantity_used }} {{ $usage->bhp->unit }})</strong>
                                </span>
                            @empty
                                <span class="text-muted small">Tidak menggunakan BHP</span>
                            @endforelse
                        </td>
                        <td>{{ $log->creator->name }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-secondary py-4">
                            Belum ada riwayat pemeliharaan terdaftar. Silakan catat pemeliharaan baru.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

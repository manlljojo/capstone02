@extends('layouts.app')

@section('title', 'Tinjau Draf Pengadaan')

@section('header_title', 'Tinjau Draf Pengadaan ' . $draft->year)
@section('header_subtitle', 'Diusulkan oleh: ' . $draft->creator->name)

@section('content')
<div class="mb-4 d-flex justify-between align-center">
    <a href="{{ route('kaprodi.review.index') }}" class="btn btn-secondary btn-sm">
        &larr; Kembali ke Daftar
    </a>
    
    @if($draft->status == 'pending_review' && $items->where('status', 'pending')->count() == 0)
        <form action="{{ route('kaprodi.review.finalize', $draft->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memfinalisasi pengadaan ini? Status pengadaan akan menjadi final dan dikirim ke staf administrasi.')">
            @csrf
            <button type="submit" class="btn btn-success">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:20px;height:20px;display:inline-block;vertical-align:middle;margin-right:6px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                Finalisasikan & Kunci Pengadaan
            </button>
        </form>
    @elseif($draft->status == 'pending_review')
        <button class="btn btn-secondary" disabled title="Tinjau semua barang terlebih dahulu">
            Finalisasikan & Kunci (Tinjauan Belum Selesai)
        </button>
    @else
        <span class="badge badge-success" style="padding:10px 16px;">Pengadaan Telah Difinalisasi</span>
    @endif
</div>

<div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-bottom: 24px;">
    <div class="stat-card glass-panel card-primary">
        <div class="stat-info">
            <span class="stat-value">{{ $items->count() }}</span>
            <span class="stat-label">Total Barang</span>
        </div>
    </div>
    <div class="stat-card glass-panel card-success">
        <div class="stat-info">
            <span class="stat-value">{{ $items->where('status', 'approved')->count() }}</span>
            <span class="stat-label">Barang Disetujui</span>
        </div>
    </div>
    <div class="stat-card glass-panel card-danger">
        <div class="stat-info">
            <span class="stat-value">{{ $items->where('status', 'rejected')->count() }}</span>
            <span class="stat-label">Barang Ditolak</span>
        </div>
    </div>
    <div class="stat-card glass-panel card-warning">
        <div class="stat-info">
            <span class="stat-value">{{ $items->where('status', 'pending')->count() }}</span>
            <span class="stat-label">Menunggu Tinjauan</span>
        </div>
    </div>
</div>

<div class="content-panel glass-panel">
    <div class="panel-header">
        <h3 class="panel-title">Barang yang Diajukan</h3>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Tipe</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah</th>
                    <th>Total Estimasi</th>
                    <th>Link Pembelian</th>
                    <th>Menggantikan Aset</th>
                    <th>Status</th>
                    @if($draft->status == 'pending_review')
                        <th style="width: 200px; text-align: center;">Tindakan</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td><strong>{{ $item->name }}</strong></td>
                        <td>
                            <span class="badge {{ $item->type == 'asset' ? 'badge-info' : 'badge-success' }}">
                                {{ $item->type == 'asset' ? 'Aset/Inventaris' : 'BHP' }}
                            </span>
                        </td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        <td>
                            @if($item->purchase_link)
                                <a href="{{ $item->purchase_link }}" target="_blank" class="btn btn-secondary btn-xs" style="color: var(--color-info);">
                                    Buka Link
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($item->replacedAsset)
                                <span style="font-size:0.85rem; color:var(--text-secondary);">
                                    {{ $item->replacedAsset->name }} <br>
                                    <code>({{ $item->replacedAsset->code }})</code>
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($item->status == 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($item->status == 'approved')
                                <span class="badge badge-success">Disetujui</span>
                            @else
                                <span class="badge badge-danger">Ditolak</span>
                            @endif
                        </td>
                        @if($draft->status == 'pending_review')
                            <td style="text-align: center;">
                                <div class="d-flex gap-2" style="justify-content: center;">
                                    <form action="{{ route('kaprodi.review.items.approve', [$draft->id, $item->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-xs" {{ $item->status == 'approved' ? 'disabled' : '' }}>
                                            Setujui
                                        </button>
                                    </form>
                                    <form action="{{ route('kaprodi.review.items.reject', [$draft->id, $item->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-xs" {{ $item->status == 'rejected' ? 'disabled' : '' }}>
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

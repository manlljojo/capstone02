@extends('layouts.app')

@section('title', 'Tinjau Draf Pengadaan')

@section('header_title', 'Tinjau Draf Pengadaan ' . $draft->year)

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <a href="{{ route('kaprodi.review.index') }}" class="btn btn-white btn-sm d-inline-flex align-items-center gap-1">
        <i class="ti ti-arrow-left"></i> Kembali ke Daftar
    </a>
    
    @if($draft->status == 'pending_review' && $items->where('status', 'pending')->count() == 0)
        <form action="{{ route('kaprodi.review.finalize', $draft->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memfinalisasi pengadaan ini? Status pengadaan akan menjadi final dan dikirim ke staf administrasi.')">
            @csrf
            <button type="submit" class="btn btn-success text-white d-inline-flex align-items-center gap-1">
                <i class="ti ti-lock"></i> Finalisasikan & Kunci Pengadaan
            </button>
        </form>
    @elseif($draft->status == 'pending_review')
        <button class="btn btn-secondary" disabled title="Tinjau semua barang terlebih dahulu">
            Finalisasikan & Kunci (Tinjauan Belum Selesai)
        </button>
    @else
        <span class="badge text-success-emphasis bg-success-subtle py-2 px-3">Pengadaan Telah Difinalisasi</span>
    @endif
</div>

<div class="row g-6 mb-6">
    <div class="col-md-3 col-sm-6 col-12">
        <div class="card card-lg shadow-sm">
            <div class="card-body">
                <div class="text-secondary fw-semibold mb-2">Total Barang</div>
                <div class="fs-3 fw-bold text-dark">{{ $items->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-12">
        <div class="card card-lg shadow-sm">
            <div class="card-body">
                <div class="text-secondary fw-semibold mb-2">Barang Disetujui</div>
                <div class="fs-3 fw-bold text-success">{{ $items->where('status', 'approved')->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-12">
        <div class="card card-lg shadow-sm">
            <div class="card-body">
                <div class="text-secondary fw-semibold mb-2">Barang Ditolak</div>
                <div class="fs-3 fw-bold text-danger">{{ $items->where('status', 'rejected')->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-12">
        <div class="card card-lg shadow-sm">
            <div class="card-body">
                <div class="text-secondary fw-semibold mb-2">Menunggu Tinjauan</div>
                <div class="fs-3 fw-bold text-warning">{{ $items->where('status', 'pending')->count() }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card card-lg shadow-sm">
    <div class="card-header border-bottom-0">
        <h5 class="mb-0">Barang yang Diajukan</h5>
    </div>

    <div class="table-responsive">
        <table class="table text-nowrap mb-0 table-centered table-hover">
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
                            @if($item->type == 'asset')
                                <span class="badge text-info-emphasis bg-info-subtle">Aset</span>
                            @else
                                <span class="badge text-success-emphasis bg-success-subtle">BHP</span>
                            @endif
                        </td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        <td>
                            @if($item->purchase_link)
                                <a href="{{ $item->purchase_link }}" target="_blank" class="btn btn-white btn-sm">
                                    Buka Link
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($item->replacedAsset)
                                <span class="small text-secondary">
                                    {{ $item->replacedAsset->name }} <br>
                                    <code>({{ $item->replacedAsset->code }})</code>
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($item->status == 'pending')
                                <span class="badge text-warning-emphasis bg-warning-subtle">Pending</span>
                            @elseif($item->status == 'approved')
                                <span class="badge text-success-emphasis bg-success-subtle">Disetujui</span>
                            @else
                                <span class="badge text-danger-emphasis bg-danger-subtle">Ditolak</span>
                            @endif
                        </td>
                        @if($draft->status == 'pending_review')
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <form action="{{ route('kaprodi.review.items.approve', [$draft->id, $item->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm text-white" {{ $item->status == 'approved' ? 'disabled' : '' }}>
                                            Setujui
                                        </button>
                                    </form>
                                    <form action="{{ route('kaprodi.review.items.reject', [$draft->id, $item->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm" {{ $item->status == 'rejected' ? 'disabled' : '' }}>
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

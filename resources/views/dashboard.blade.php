@extends('layouts.app')

@section('title', 'Dashboard')

@section('header_title', 'Dashboard')

@section('content')
<div class="dashboard-wrapper">
    <!-- 1. ADMIN DASHBOARD -->
    @if(Auth::user()->isAdmin())
        <div class="row g-6 mb-6">
            <div class="col-md-6 col-xl-6">
                <div class="card card-lg shadow-sm">
                    <div class="card-body d-flex flex-column gap-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-shape icon-lg rounded-circle bg-primary-darker text-primary-lighter d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: rgba(99, 102, 241, 0.1); color: #6366f1;">
                                <i class="ti ti-users fs-4"></i>
                            </div>
                            <div class="text-secondary fw-semibold">Total Pengguna</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center lh-1">
                            <div class="fs-3 fw-bold text-dark">{{ $data['total_users'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-6">
                <div class="card card-lg shadow-sm">
                    <div class="card-body d-flex flex-column gap-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-shape icon-lg rounded-circle bg-info-darker text-info-lighter d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: rgba(6, 182, 212, 0.1); color: #06b6d4;">
                                <i class="ti ti-door-enter fs-4"></i>
                            </div>
                            <div class="text-secondary fw-semibold">Total Ruangan</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center lh-1">
                            <div class="fs-3 fw-bold text-dark">{{ $data['total_rooms'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-6 mb-6">
            <!-- Recent Users -->
            <div class="col-xl-6 col-lg-6 col-12">
                <div class="card card-lg shadow-sm">
                    <div class="card-header border-bottom-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Pengguna Terbaru</h5>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-white btn-sm">Lihat Semua</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-nowrap mb-0 table-centered table-hover">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Peran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['recent_users'] as $u)
                                    <tr>
                                        <td><strong>{{ $u->name }}</strong></td>
                                        <td>{{ $u->email }}</td>
                                        <td>
                                            <span class="badge text-info-emphasis bg-info-subtle text-uppercase">{{ $u->role }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-secondary py-4">Belum ada data pengguna.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Rooms -->
            <div class="col-xl-6 col-lg-6 col-12">
                <div class="card card-lg shadow-sm">
                    <div class="card-header border-bottom-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Ruangan Terbaru</h5>
                        <a href="{{ route('admin.rooms.index') }}" class="btn btn-white btn-sm">Lihat Semua</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-nowrap mb-0 table-centered table-hover">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Ruangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['recent_rooms'] as $r)
                                    <tr>
                                        <td><code>{{ $r->code }}</code></td>
                                        <td><strong>{{ $r->name }}</strong></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-secondary py-4">Belum ada data ruangan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- 2. KEPALA LABORATORIUM DASHBOARD -->
    @if(Auth::user()->isKalab())
        <div class="row g-6 mb-6">
            <div class="col-md-4 col-xl-4">
                <div class="card card-lg shadow-sm">
                    <div class="card-body d-flex flex-column gap-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-shape icon-lg rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: rgba(99, 102, 241, 0.1); color: #6366f1;">
                                <i class="ti ti-file-text fs-4"></i>
                            </div>
                            <div class="text-secondary fw-semibold">Total Pengajuan</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center lh-1">
                            <div class="fs-3 fw-bold text-dark">{{ $data['total_drafts'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-xl-4">
                <div class="card card-lg shadow-sm">
                    <div class="card-body d-flex flex-column gap-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-shape icon-lg rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                                <i class="ti ti-clock-hour-4 fs-4"></i>
                            </div>
                            <div class="text-secondary fw-semibold">Pending Review</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center lh-1">
                            <div class="fs-3 fw-bold text-dark">{{ $data['pending_drafts'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-xl-4">
                <div class="card card-lg shadow-sm">
                    <div class="card-body d-flex flex-column gap-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-shape icon-lg rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: rgba(16, 185, 129, 0.1); color: #10b981;">
                                <i class="ti ti-clipboard-check fs-4"></i>
                            </div>
                            <div class="text-secondary fw-semibold">Telah Disetujui/Final</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center lh-1">
                            <div class="fs-3 fw-bold text-dark">{{ $data['finalized_drafts'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-6 mb-6">
            <div class="col-12">
                <div class="card card-lg shadow-sm">
                    <div class="card-header border-bottom-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Draf Pengadaan Terbaru Anda</h5>
                        <a href="{{ route('kalab.drafts.index') }}" class="btn btn-primary btn-sm">Mulai Pengadaan</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-nowrap mb-0 table-centered table-hover">
                            <thead>
                                <tr>
                                    <th>Tahun</th>
                                    <th>Status</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['recent_drafts'] as $d)
                                    <tr>
                                        <td><strong>{{ $d->year }}</strong></td>
                                        <td>
                                            @if($d->status == 'draft')
                                                <span class="badge text-warning-emphasis bg-warning-subtle">Draft</span>
                                            @elseif($d->status == 'pending_review')
                                                <span class="badge text-info-emphasis bg-info-subtle">Pending Review</span>
                                            @else
                                                <span class="badge text-success-emphasis bg-success-subtle">Finalized & Locked</span>
                                            @endif
                                        </td>
                                        <td>{{ $d->created_at->format('d M Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('kalab.drafts.show', $d->id) }}" class="btn btn-white btn-sm">
                                                Detail / Edit Items
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-secondary py-4">Belum ada draf pengadaan yang dibuat.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- 3. KETUA PROGRAM STUDI DASHBOARD -->
    @if(Auth::user()->isKaprodi())
        <div class="row g-6 mb-6">
            <div class="col-md-6 col-xl-6">
                <div class="card card-lg shadow-sm">
                    <div class="card-body d-flex flex-column gap-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-shape icon-lg rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                                <i class="ti ti-alert-triangle fs-4"></i>
                            </div>
                            <div class="text-secondary fw-semibold">Perlu Review</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center lh-1">
                            <div class="fs-3 fw-bold text-dark">{{ $data['pending_reviews'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-6">
                <div class="card card-lg shadow-sm">
                    <div class="card-body d-flex flex-column gap-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-shape icon-lg rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: rgba(16, 185, 129, 0.1); color: #10b981;">
                                <i class="ti ti-lock fs-4"></i>
                            </div>
                            <div class="text-secondary fw-semibold">Telah Difinalisasi</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center lh-1">
                            <div class="fs-3 fw-bold text-dark">{{ $data['finalized_drafts'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-6 mb-6">
            <div class="col-12">
                <div class="card card-lg shadow-sm">
                    <div class="card-header border-bottom-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Pengajuan yang Perlu Direview</h5>
                        <a href="{{ route('kaprodi.review.index') }}" class="btn btn-primary btn-sm">Tinjau Pengadaan</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-nowrap mb-0 table-centered table-hover">
                            <thead>
                                <tr>
                                    <th>Tahun</th>
                                    <th>Pengusul</th>
                                    <th>Status</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['recent_reviews'] as $r)
                                    <tr>
                                        <td><strong>{{ $r->year }}</strong></td>
                                        <td>{{ $r->creator->name }}</td>
                                        <td>
                                            <span class="badge text-warning-emphasis bg-warning-subtle">Menunggu Review</span>
                                        </td>
                                        <td>{{ $r->updated_at->format('d M Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('kaprodi.review.show', $r->id) }}" class="btn btn-warning btn-sm">
                                                Review Items
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-secondary py-4">Tidak ada pengajuan yang menunggu review.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- 4. STAF ADMINISTRASI DASHBOARD -->
    @if(Auth::user()->isStafAdmin())
        <div class="row g-6 mb-6">
            <div class="col-md-4 col-xl-4">
                <div class="card card-lg shadow-sm">
                    <div class="card-body d-flex flex-column gap-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-shape icon-lg rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: rgba(16, 185, 129, 0.1); color: #10b981;">
                                <i class="ti ti-circle-check fs-4"></i>
                            </div>
                            <div class="text-secondary fw-semibold">Pengadaan Disetujui</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center lh-1">
                            <div class="fs-3 fw-bold text-dark">{{ $data['finalized_drafts_count'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-xl-4">
                <div class="card card-lg shadow-sm">
                    <div class="card-body d-flex flex-column gap-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-shape icon-lg rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                                <i class="ti ti-barcode fs-4"></i>
                            </div>
                            <div class="text-secondary fw-semibold">Aset Belum Dilabel</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center lh-1">
                            <div class="fs-3 fw-bold text-dark">{{ $data['unlabeled_assets'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-xl-4">
                <div class="card card-lg shadow-sm">
                    <div class="card-body d-flex flex-column gap-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-shape icon-lg rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: rgba(6, 182, 212, 0.1); color: #06b6d4;">
                                <i class="ti ti-box fs-4"></i>
                            </div>
                            <div class="text-secondary fw-semibold">Total Aset Fisik</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center lh-1">
                            <div class="fs-3 fw-bold text-dark">{{ $data['total_assets'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-6 mb-6">
            <div class="col-12">
                <div class="card card-lg shadow-sm">
                    <div class="card-header border-bottom-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="mb-0">Aset yang Baru Terdaftar</h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('staf_admin.approved.index') }}" class="btn btn-primary btn-sm">Input Penerimaan</a>
                            <a href="{{ route('staf_admin.assets.index') }}" class="btn btn-white btn-sm">Kelola Label</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-nowrap mb-0 table-centered table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Aset</th>
                                    <th>Kode Label</th>
                                    <th>Ruangan</th>
                                    <th>Status</th>
                                    <th>Barcode/QR</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['recent_assets'] as $a)
                                    <tr>
                                        <td><strong>{{ $a->name }}</strong></td>
                                        <td>
                                            @if($a->code)
                                                <span class="badge text-info-emphasis bg-info-subtle font-monospace" style="font-size: 0.8rem; letter-spacing: 0.05em;">UKM: {{ $a->code }}</span>
                                            @else
                                                <span class="badge text-danger-emphasis bg-danger-subtle" style="font-size: 0.75rem;">BELUM SET</span>
                                            @endif
                                        </td>
                                        <td>{{ $a->room->name ?? 'Belum Dialokasikan' }}</td>
                                        <td>
                                            @if($a->status == 'baik')
                                                <span class="badge text-success-emphasis bg-success-subtle">Baik</span>
                                            @elseif($a->status == 'rusak')
                                                <span class="badge text-danger-emphasis bg-danger-subtle">Rusak</span>
                                            @elseif($a->status == 'maintenance')
                                                <span class="badge text-warning-emphasis bg-warning-subtle">Maintenance</span>
                                            @else
                                                <span class="badge text-secondary-emphasis bg-secondary-subtle">Diarsipkan</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($a->barcode_photo)
                                                <span class="text-cyan d-inline-flex align-items-center gap-1 small">
                                                    <i class="ti ti-qrcode fs-5"></i> Ada QR
                                                </span>
                                            @else
                                                <span class="text-muted small">Belum Upload</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-secondary py-4">Belum ada aset terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- 5. STAF LABORATORIUM DASHBOARD -->
    @if(Auth::user()->isStafLab())
        <div class="row g-6 mb-6">
            <div class="col-md-3 col-sm-6 col-12">
                <div class="card card-lg shadow-sm">
                    <div class="card-body d-flex flex-column gap-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-shape icon-lg rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: rgba(99, 102, 241, 0.1); color: #6366f1;">
                                <i class="ti ti-box fs-4"></i>
                            </div>
                            <div class="text-secondary fw-semibold">Total Aset</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center lh-1">
                            <div class="fs-3 fw-bold text-dark">{{ $data['total_assets'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-12">
                <div class="card card-lg shadow-sm">
                    <div class="card-body d-flex flex-column gap-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-shape icon-lg rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: rgba(239, 68, 68, 0.1); color: #ef4444;">
                                <i class="ti ti-alert-circle fs-4"></i>
                            </div>
                            <div class="text-secondary fw-semibold">Aset Rusak</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center lh-1">
                            <div class="fs-3 fw-bold text-dark">{{ $data['broken_assets'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-12">
                <div class="card card-lg shadow-sm">
                    <div class="card-body d-flex flex-column gap-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-shape icon-lg rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                                <i class="ti ti-tool fs-4"></i>
                            </div>
                            <div class="text-secondary fw-semibold">Aset Maintenance</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center lh-1">
                            <div class="fs-3 fw-bold text-dark">{{ $data['maintenance_assets'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-12">
                <div class="card card-lg shadow-sm">
                    <div class="card-body d-flex flex-column gap-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-shape icon-lg rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: rgba(6, 182, 212, 0.1); color: #06b6d4;">
                                <i class="ti ti-package fs-4"></i>
                            </div>
                            <div class="text-secondary fw-semibold">BHP Stok Rendah</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center lh-1">
                            <div class="fs-3 fw-bold text-dark">{{ $data['low_stock_bhp'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-6 mb-6">
            <div class="col-12">
                <div class="card card-lg shadow-sm">
                    <div class="card-header border-bottom-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="mb-0">Riwayat Pemeliharaan Terakhir</h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('staf_lab.bhp.index') }}" class="btn btn-white btn-sm">Lihat Stok BHP</a>
                            <a href="{{ route('staf_lab.maintenance.index') }}" class="btn btn-primary btn-sm">Log Maintenance Baru</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-nowrap mb-0 table-centered table-hover">
                            <thead>
                                <tr>
                                    <th>Aset</th>
                                    <th>Tanggal</th>
                                    <th>Deskripsi</th>
                                    <th>Kondisi Sebelum/Sesudah</th>
                                    <th>Petugas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['recent_maintenances'] as $m)
                                    <tr>
                                        <td><strong>{{ $m->asset->name }}</strong> <br> <code style="font-size:0.75rem;">{{ $m->asset->code }}</code></td>
                                        <td>{{ \Carbon\Carbon::parse($m->maintenance_date)->format('d M Y') }}</td>
                                        <td style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $m->description }}</td>
                                        <td>
                                            <span class="badge text-info-emphasis bg-info-subtle">{{ $m->status_before }}</span>
                                            <span class="text-muted px-1">→</span>
                                            <span class="badge text-success-emphasis bg-success-subtle">{{ $m->status_after }}</span>
                                        </td>
                                        <td>{{ $m->creator->name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-secondary py-4">Belum ada riwayat pemeliharaan dicatat.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

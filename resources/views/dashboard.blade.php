@extends('layouts.app')

@section('title', 'Dashboard')

@section('header_title', 'Dashboard')
@section('header_subtitle', 'Selamat datang kembali di sistem pengelolaan laboratorium.')

@section('content')
<div class="dashboard-wrapper">
    <!-- 1. ADMIN DASHBOARD -->
    @if(Auth::user()->isAdmin())
        <div class="stats-grid">
            <div class="stat-card glass-panel card-primary">
                <div class="stat-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $data['total_users'] }}</span>
                    <span class="stat-label">Total Pengguna</span>
                </div>
            </div>

            <div class="stat-card glass-panel card-info">
                <div class="stat-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $data['total_rooms'] }}</span>
                    <span class="stat-label">Total Ruangan</span>
                </div>
            </div>
        </div>

        <div class="grid-2">
            <!-- Recent Users -->
            <div class="content-panel glass-panel">
                <div class="panel-header">
                    <h3 class="panel-title">Pengguna Terbaru</h3>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-xs">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table">
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
                                    <td>{{ $u->name }}</td>
                                    <td>{{ $u->email }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $u->role }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align: center; color: var(--text-muted);">Belum ada data pengguna.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Rooms -->
            <div class="content-panel glass-panel">
                <div class="panel-header">
                    <h3 class="panel-title">Ruangan Terbaru</h3>
                    <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary btn-xs">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table">
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
                                    <td>{{ $r->name }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" style="text-align: center; color: var(--text-muted);">Belum ada data ruangan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- 2. KEPALA LABORATORIUM DASHBOARD -->
    @if(Auth::user()->isKalab())
        <div class="stats-grid">
            <div class="stat-card glass-panel card-primary">
                <div class="stat-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $data['total_drafts'] }}</span>
                    <span class="stat-label">Total Pengajuan</span>
                </div>
            </div>

            <div class="stat-card glass-panel card-warning">
                <div class="stat-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $data['pending_drafts'] }}</span>
                    <span class="stat-label">Pending Review</span>
                </div>
            </div>

            <div class="stat-card glass-panel card-success">
                <div class="stat-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $data['finalized_drafts'] }}</span>
                    <span class="stat-label">Telah Disetujui/Final</span>
                </div>
            </div>
        </div>

        <div class="content-panel glass-panel">
            <div class="panel-header">
                <h3 class="panel-title">Draf Pengadaan Terbaru Anda</h3>
                <a href="{{ route('kalab.drafts.index') }}" class="btn btn-primary btn-sm">Mulai Pengadaan</a>
            </div>
            <div class="table-responsive">
                <table class="table">
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
                                        <span class="badge badge-warning">Draft</span>
                                    @elseif($d->status == 'pending_review')
                                        <span class="badge badge-info">Pending Review</span>
                                    @else
                                        <span class="badge badge-success">Finalized & Locked</span>
                                    @endif
                                </td>
                                <td>{{ $d->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('kalab.drafts.show', $d->id) }}" class="btn btn-secondary btn-xs">
                                        Detail / Edit Items
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--text-muted);">Belum ada draf pengadaan yang dibuat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- 3. KETUA PROGRAM STUDI DASHBOARD -->
    @if(Auth::user()->isKaprodi())
        <div class="stats-grid">
            <div class="stat-card glass-panel card-warning">
                <div class="stat-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $data['pending_reviews'] }}</span>
                    <span class="stat-label">Perlu Review</span>
                </div>
            </div>

            <div class="stat-card glass-panel card-success">
                <div class="stat-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $data['finalized_drafts'] }}</span>
                    <span class="stat-label">Telah Difinalisasi</span>
                </div>
            </div>
        </div>

        <div class="content-panel glass-panel">
            <div class="panel-header">
                <h3 class="panel-title">Pengajuan yang Perlu Direview</h3>
                <a href="{{ route('kaprodi.review.index') }}" class="btn btn-primary btn-sm">Tinjau Pengadaan</a>
            </div>
            <div class="table-responsive">
                <table class="table">
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
                                    <span class="badge badge-warning">Menunggu Review</span>
                                </td>
                                <td>{{ $r->updated_at->format('d M Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('kaprodi.review.show', $r->id) }}" class="btn btn-warning btn-xs">
                                        Review Items
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; color: var(--text-muted);">Tidak ada pengajuan yang menunggu review.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- 4. STAF ADMINISTRASI DASHBOARD -->
    @if(Auth::user()->isStafAdmin())
        <div class="stats-grid">
            <div class="stat-card glass-panel card-success">
                <div class="stat-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $data['finalized_drafts_count'] }}</span>
                    <span class="stat-label">Pengadaan Disetujui</span>
                </div>
            </div>

            <div class="stat-card glass-panel card-warning">
                <div class="stat-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                    </svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $data['unlabeled_assets'] }}</span>
                    <span class="stat-label">Aset Belum Dilabel</span>
                </div>
            </div>

            <div class="stat-card glass-panel card-info">
                <div class="stat-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $data['total_assets'] }}</span>
                    <span class="stat-label">Total Aset Fisik</span>
                </div>
            </div>
        </div>

        <div class="content-panel glass-panel">
            <div class="panel-header">
                <h3 class="panel-title">Aset yang Baru Terdaftar</h3>
                <div class="d-flex gap-2">
                    <a href="{{ route('staf_admin.approved.index') }}" class="btn btn-primary btn-sm">Input Penerimaan</a>
                    <a href="{{ route('staf_admin.assets.index') }}" class="btn btn-secondary btn-sm">Kelola Label</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
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
                                <td><code>{{ $a->code ?? 'BELUM SET' }}</code></td>
                                <td>{{ $a->room->name ?? 'Belum Dialokasikan' }}</td>
                                <td>
                                    @if($a->status == 'baik')
                                        <span class="badge badge-success">Baik</span>
                                    @elseif($a->status == 'rusak')
                                        <span class="badge badge-danger">Rusak</span>
                                    @elseif($a->status == 'maintenance')
                                        <span class="badge badge-warning">Maintenance</span>
                                    @else
                                        <span class="badge badge-info">Diarsipkan</span>
                                    @endif
                                </td>
                                <td>
                                    @if($a->barcode_photo)
                                        <span class="asset-badge-qr">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:16px;height:16px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                            </svg>
                                            Ada QR
                                        </span>
                                    @else
                                        <span style="color: var(--text-muted); font-size: 0.8rem;">Belum Upload</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; color: var(--text-muted);">Belum ada aset terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- 5. STAF LABORATORIUM DASHBOARD -->
    @if(Auth::user()->isStafLab())
        <div class="stats-grid">
            <div class="stat-card glass-panel card-primary">
                <div class="stat-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $data['total_assets'] }}</span>
                    <span class="stat-label">Total Aset Terdaftar</span>
                </div>
            </div>

            <div class="stat-card glass-panel card-danger">
                <div class="stat-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $data['broken_assets'] }}</span>
                    <span class="stat-label">Aset Kondisi Rusak</span>
                </div>
            </div>

            <div class="stat-card glass-panel card-warning">
                <div class="stat-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    </svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $data['maintenance_assets'] }}</span>
                    <span class="stat-label">Aset Sedang Maintenance</span>
                </div>
            </div>

            <div class="stat-card glass-panel card-info">
                <div class="stat-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $data['low_stock_bhp'] }}</span>
                    <span class="stat-label">BHP Stok Rendah (&lt;5)</span>
                </div>
            </div>
        </div>

        <div class="content-panel glass-panel">
            <div class="panel-header">
                <h3 class="panel-title">Riwayat Pemeliharaan Terakhir</h3>
                <div class="d-flex gap-2">
                    <a href="{{ route('staf_lab.bhp.index') }}" class="btn btn-secondary btn-sm">Lihat Stok BHP</a>
                    <a href="{{ route('staf_lab.maintenance.index') }}" class="btn btn-primary btn-sm">Log Maintenance Baru</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
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
                                    <span class="badge badge-info">{{ $m->status_before }}</span>
                                    <span style="color:var(--text-secondary)">→</span>
                                    <span class="badge badge-success">{{ $m->status_after }}</span>
                                </td>
                                <td>{{ $m->creator->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; color: var(--text-muted);">Belum ada riwayat pemeliharaan dicatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection

<!DOCTYPE html>
<html lang="id" data-bs-theme="light" class="expanded">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Capstone Lab Asset</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    
    <!-- Dasher Theme CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Color modes script -->
    <script src="{{ asset('assets/js/vendors/color-modes.js') }}"></script>
    <script>
      if (localStorage.getItem('sidebarExpanded') === 'false') {
        document.documentElement.classList.add('collapsed');
        document.documentElement.classList.remove('expanded');
      } else {
        document.documentElement.classList.remove('collapsed');
        document.documentElement.classList.add('expanded');
      }
    </script>
    @yield('styles')
</head>
<body>
    <!-- Vertical Sidebar -->
    <div id="miniSidebar">
        <!-- Brand logo -->
        <div class="brand-logo">
            <a class="d-none d-md-flex align-items-center gap-2 text-decoration-none" href="{{ route('dashboard') }}">
                <i class="ti ti-flask text-primary fs-3 flex-shrink-0"></i>
                <span class="fw-bold fs-4 site-logo-text">CAPSTONE LAB</span>
            </a>
        </div>
        
        <!-- Navigation Menu -->
        <ul class="navbar-nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <span class="nav-icon"><i class="ti ti-layout-dashboard fs-5"></i></span>
                    <span class="text">Dashboard</span>
                </a>
            </li>

            <!-- Administrator Menu -->
            @if(Auth::user()->isAdmin())
                <li class="nav-item">
                    <div class="nav-heading">Administrator</div>
                    <hr class="mx-5 nav-line mb-1" />
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <span class="nav-icon"><i class="ti ti-users fs-5"></i></span>
                        <span class="text">Kelola Pengguna</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('admin.rooms.*') ? 'active' : '' }}" href="{{ route('admin.rooms.index') }}">
                        <span class="nav-icon"><i class="ti ti-door-enter fs-5"></i></span>
                        <span class="text">Kelola Ruangan</span>
                    </a>
                </li>
            @endif

            <!-- Kalab Menu -->
            @if(Auth::user()->isKalab())
                <li class="nav-item">
                    <div class="nav-heading">Kepala Lab</div>
                    <hr class="mx-5 nav-line mb-1" />
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('kalab.drafts.*') ? 'active' : '' }}" href="{{ route('kalab.drafts.index') }}">
                        <span class="nav-icon"><i class="ti ti-file-text fs-5"></i></span>
                        <span class="text">Pengadaan Aset</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('kalab.history') ? 'active' : '' }}" href="{{ route('kalab.history') }}">
                        <span class="nav-icon"><i class="ti ti-history fs-5"></i></span>
                        <span class="text">Riwayat Pengadaan</span>
                    </a>
                </li>
            @endif

            <!-- Kaprodi Menu -->
            @if(Auth::user()->isKaprodi())
                <li class="nav-item">
                    <div class="nav-heading">Ketua Program Studi</div>
                    <hr class="mx-5 nav-line mb-1" />
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('kaprodi.review.*') ? 'active' : '' }}" href="{{ route('kaprodi.review.index') }}">
                        <span class="nav-icon"><i class="ti ti-checkbox fs-5"></i></span>
                        <span class="text">Review Pengadaan</span>
                    </a>
                </li>
            @endif

            <!-- Staf Admin Menu -->
            @if(Auth::user()->isStafAdmin())
                <li class="nav-item">
                    <div class="nav-heading">Staf Administrasi</div>
                    <hr class="mx-5 nav-line mb-1" />
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('staf_admin.approved.*') ? 'active' : '' }}" href="{{ route('staf_admin.approved.index') }}">
                        <span class="nav-icon"><i class="ti ti-clipboard-check fs-5"></i></span>
                        <span class="text">Penerimaan Barang</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('staf_admin.assets.*') ? 'active' : '' }}" href="{{ route('staf_admin.assets.index') }}">
                        <span class="nav-icon"><i class="ti ti-tags fs-5"></i></span>
                        <span class="text">Pelabelan Aset</span>
                    </a>
                </li>
            @endif

            <!-- Staf Lab Menu -->
            @if(Auth::user()->isStafLab())
                <li class="nav-item">
                    <div class="nav-heading">Staf Laboratorium</div>
                    <hr class="mx-5 nav-line mb-1" />
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('staf_lab.bhp.*') ? 'active' : '' }}" href="{{ route('staf_lab.bhp.index') }}">
                        <span class="nav-icon"><i class="ti ti-package fs-5"></i></span>
                        <span class="text">Stok BHP</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('staf_lab.maintenance.*') ? 'active' : '' }}" href="{{ route('staf_lab.maintenance.index') }}">
                        <span class="nav-icon"><i class="ti ti-tool fs-5"></i></span>
                        <span class="text">Pemeliharaan Aset</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>

    <!-- Main Content Wrapper -->
    <div id="content" class="position-relative h-100">
        <!-- Navbar Glass Header (Sticks to the top) -->
        <div class="navbar-glass navbar navbar-expand-lg px-0 px-lg-4">
            <div class="container-fluid px-lg-0">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <div class="d-flex align-items-center gap-2">
                        <!-- Sidebar Toggle (Mobile) -->
                        <div class="d-block d-lg-none">
                            <a class="sidebar-toggle text-inherit p-2" href="javascript:void(0)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-menu-2">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M4 6l16 0"></path>
                                    <path d="M4 12l16 0"></path>
                                    <path d="M4 18l16 0"></path>
                                </svg>
                            </a>
                        </div>
                        
                        <!-- Sidebar Toggle (Desktop) -->
                        <div class="d-none d-lg-block">
                            <a class="sidebar-toggle d-flex texttooltip p-2 text-decoration-none align-items-center gap-1" href="javascript:void(0)">
                                <span class="collapse-mini">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-arrow-bar-left text-secondary">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M4 12l10 0"></path>
                                        <path d="M4 12l4 4"></path>
                                        <path d="M4 12l4 -4"></path>
                                        <path d="M20 4l0 16"></path>
                                    </svg>
                                </span>
                                <span class="collapse-expanded">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-arrow-bar-right text-secondary">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M20 12l-10 0"></path>
                                        <path d="M20 12l-4 4"></path>
                                        <path d="M20 12l-4 -4"></path>
                                        <path d="M4 4l0 16"></path>
                                    </svg>
                                </span>
                            </a>
                        </div>

                        <!-- Header Titles -->
                        <div class="ms-3">
                            <h1 class="h4 mb-0 fw-bold text-dark">@yield('header_title')</h1>
                            <p class="small text-muted mb-0 d-none d-sm-block">@yield('header_subtitle', 'Sistem Digitalisasi Aset & BHP')</p>
                        </div>
                    </div>

                    <!-- Right Profile Section -->
                    <div class="d-flex align-items-center gap-3">
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle text-dark" id="userProfileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="d-flex flex-column text-end d-none d-sm-block">
                                    <span class="fw-semibold small lh-1">{{ Auth::user()->name }}</span>
                                    <span class="text-muted small text-uppercase" style="font-size: 10px;">{{ str_replace('_', ' ', Auth::user()->role) }}</span>
                                </div>
                                <!-- Circle Initial Avatar -->
                                <div class="rounded-circle bg-primary text-white fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px; background-color: var(--bs-primary) !important;">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="userProfileDropdown">
                                <li class="p-3 border-bottom">
                                    <span class="d-block fw-semibold text-dark">{{ Auth::user()->name }}</span>
                                    <span class="d-block text-muted small">{{ Auth::user()->email }}</span>
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2">
                                            <i class="ti ti-logout"></i> Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="custom-container py-5 px-4">
            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ti ti-circle-check fs-5 me-2"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ti ti-alert-triangle fs-5 me-2"></i>
                    <span>{{ session('error') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ti ti-alert-triangle fs-5 me-2"></i>
                    <div class="d-inline-block">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap 5 JavaScript Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Simplebar JS -->
    <script src="https://cdn.jsdelivr.net/npm/simplebar@6.2.5/dist/simplebar.min.js"></script>
    <!-- Theme Script -->
    <script src="{{ asset('assets/js/vendors/sidebarnav.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @yield('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Capstone Lab Asset</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-logo">
                <!-- Lab Icon SVG -->
                <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v1.244c0 .892-.612 1.646-1.482 1.815a4.88 4.88 0 00-3.071 1.911a1.25 1.25 0 01-1.886.096L2.302 7.161a1.25 1.25 0 01.096-1.886a8.38 8.38 0 015.263-3.275a1.25 1.25 0 011.517 1.104zM14.25 3.104v1.244c0 .892.612 1.646 1.482 1.815a4.88 4.88 0 013.071 1.911a1.25 1.25 0 001.886.096l1.009-1.008a1.25 1.25 0 00-.096-1.886a8.38 8.38 0 00-5.263-3.275a1.25 1.25 0 00-1.517 1.104zM12 9.75a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.22 18.28a11.95 11.95 0 005.06 3.22c.451.154.918-.216.918-.694V19.5a3 3 0 013-3h1.5a3 3 0 013 3v1.306c0 .478.467.848.918.694a11.95 11.95 0 005.06-3.22a.75.75 0 00-.022-1.086l-1.127-1.127a3 3 0 01-.879-2.121v-.75a3 3 0 01.879-2.121l1.127-1.127a.75.75 0 00.022-1.086a11.95 11.95 0 00-5.06-3.22A.75.75 0 0017.25 7.5v1.306a3 3 0 01-3 3h-1.5a3 3 0 01-3-3V7.5a.75.75 0 00-.918-.694a11.95 11.95 0 00-5.06 3.22a.75.75 0 00.022 1.086l1.127 1.127A3 3 0 017.5 14.364v.75a3 3 0 01-.879 2.121l-1.127 1.127a.75.75 0 00-.022 1.086z" />
                </svg>
                <span class="logo-text">CAPSTONE LAB</span>
            </div>

            <ul class="sidebar-menu">
                <li class="menu-item {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                        </svg>
                        Dashboard
                    </a>
                </li>

                <!-- Admin Menu -->
                @if(Auth::user()->isAdmin())
                    <li class="menu-section">Administrator</li>
                    <li class="menu-item {{ Request::routeIs('admin.users.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.users.index') }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Kelola Pengguna
                        </a>
                    </li>
                    <li class="menu-item {{ Request::routeIs('admin.rooms.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.rooms.index') }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Kelola Ruangan
                        </a>
                    </li>
                @endif

                <!-- Kalab Menu -->
                @if(Auth::user()->isKalab())
                    <li class="menu-section">Kepala Lab</li>
                    <li class="menu-item {{ Request::routeIs('kalab.drafts.*') ? 'active' : '' }}">
                        <a href="{{ route('kalab.drafts.index') }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Pengadaan Aset
                        </a>
                    </li>
                    <li class="menu-item {{ Request::routeIs('kalab.history') ? 'active' : '' }}">
                        <a href="{{ route('kalab.history') }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Riwayat Pengadaan
                        </a>
                    </li>
                @endif

                <!-- Kaprodi Menu -->
                @if(Auth::user()->isKaprodi())
                    <li class="menu-section">Ketua Program Studi</li>
                    <li class="menu-item {{ Request::routeIs('kaprodi.review.*') ? 'active' : '' }}">
                        <a href="{{ route('kaprodi.review.index') }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            Review Pengadaan
                        </a>
                    </li>
                @endif

                <!-- Staf Admin Menu -->
                @if(Auth::user()->isStafAdmin())
                    <li class="menu-section">Staf Administrasi</li>
                    <li class="menu-item {{ Request::routeIs('staf_admin.approved.*') ? 'active' : '' }}">
                        <a href="{{ route('staf_admin.approved.index') }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            Penerimaan Barang
                        </a>
                    </li>
                    <li class="menu-item {{ Request::routeIs('staf_admin.assets.*') ? 'active' : '' }}">
                        <a href="{{ route('staf_admin.assets.index') }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                            </svg>
                            Pelabelan Aset
                        </a>
                    </li>
                @endif

                <!-- Staf Lab Menu -->
                @if(Auth::user()->isStafLab())
                    <li class="menu-section">Staf Laboratorium</li>
                    <li class="menu-item {{ Request::routeIs('staf_lab.bhp.*') ? 'active' : '' }}">
                        <a href="{{ route('staf_lab.bhp.index') }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Stok BHP
                        </a>
                    </li>
                    <li class="menu-item {{ Request::routeIs('staf_lab.maintenance.*') ? 'active' : '' }}">
                        <a href="{{ route('staf_lab.maintenance.index') }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Pemeliharaan Aset
                        </a>
                    </li>
                @endif
            </ul>

            <!-- Sidebar User Box -->
            <div class="sidebar-user">
                <div class="user-info">
                    <span class="user-name" title="{{ Auth::user()->name }}">{{ Auth::user()->name }}</span>
                    <span class="user-role">{{ str_replace('_', ' ', Auth::user()->role) }}</span>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="main-wrapper">
            <!-- Header -->
            <header class="top-header">
                <div class="header-title">
                    <h1>@yield('header_title')</h1>
                    <p>@yield('header_subtitle', 'Sistem Digitalisasi Aset & BHP')</p>
                </div>
            </header>

            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success glass-panel">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger glass-panel">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger glass-panel">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;flex-shrink:0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div style="display:flex;flex-direction:column;">
                        @foreach ($errors->all() as $error)
                            <span>{{ $error }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </main>
    </div>

    @yield('scripts')
</body>
</html>

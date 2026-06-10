<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Capstone Lab</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card glass-panel">
            <div class="auth-header">
                <!-- Lab Icon SVG -->
                <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v1.244c0 .892-.612 1.646-1.482 1.815a4.88 4.88 0 00-3.071 1.911a1.25 1.25 0 01-1.886.096L2.302 7.161a1.25 1.25 0 01.096-1.886a8.38 8.38 0 015.263-3.275a1.25 1.25 0 011.517 1.104zM14.25 3.104v1.244c0 .892.612 1.646 1.482 1.815a4.88 4.88 0 013.071 1.911a1.25 1.25 0 001.886.096l1.009-1.008a1.25 1.25 0 00-.096-1.886a8.38 8.38 0 00-5.263-3.275a1.25 1.25 0 00-1.517 1.104zM12 9.75a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.22 18.28a11.95 11.95 0 005.06 3.22c.451.154.918-.216.918-.694V19.5a3 3 0 013-3h1.5a3 3 0 013 3v1.306c0 .478.467.848.918.694a11.95 11.95 0 005.06-3.22a.75.75 0 00-.022-1.086l-1.127-1.127a3 3 0 01-.879-2.121v-.75a3 3 0 01.879-2.121l1.127-1.127a.75.75 0 00.022-1.086a11.95 11.95 0 00-5.06-3.22A.75.75 0 0017.25 7.5v1.306a3 3 0 01-3 3h-1.5a3 3 0 01-3-3V7.5a.75.75 0 00-.918-.694a11.95 11.95 0 00-5.06 3.22a.75.75 0 00.022 1.086l1.127 1.127A3 3 0 017.5 14.364v.75a3 3 0 01-.879 2.121l-1.127 1.127a.75.75 0 00-.022 1.086z" />
                </svg>
                <h2>Selamat Datang</h2>
                <p>Sistem Aset & BHP Laboratorium</p>
            </div>

            <!-- Session Status & Errors -->
            @if(session('success'))
                <div class="alert alert-success glass-panel">
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger glass-panel">
                    <div style="display:flex;flex-direction:column;">
                        @foreach ($errors->all() as $error)
                            <span>{{ $error }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="nama@domain.com">
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password" class="form-control" required placeholder="••••••••">
                </div>

                <div class="form-group" style="display:flex; align-items:center; gap:8px; margin-top:8px;">
                    <input id="remember_me" type="checkbox" name="remember" style="accent-color: var(--color-primary); cursor:pointer;">
                    <label for="remember_me" class="form-label" style="margin-bottom:0; cursor:pointer; font-size:0.8rem;">Ingat Saya</label>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%; margin-top:16px; padding:12px;">
                    Masuk
                </button>
            </form>
            
            <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--border-glass); text-align: center;">
            </div>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Capstone Lab Asset</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    
    <!-- Dasher Theme CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .form-control {
            background-color: #ffffff !important;
            color: #1f2937 !important;
            border: 1px solid #d1d5db !important;
        }
        .form-control::placeholder {
            color: #9ca3af !important;
        }
        .form-control:focus {
            background-color: #ffffff !important;
            color: #1f2937 !important;
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25) !important;
        }
    </style>
</head>
<body>
    <main class="d-flex flex-column justify-content-center vh-100 bg-light-subtle">
        <section>
            <div class="container">
                <div class="row mb-5">
                    <div class="col-xl-4 offset-xl-4 col-md-12 col-12">
                        <div class="text-center">
                            <a href="#" class="fs-2 fw-bold d-flex align-items-center gap-2 justify-content-center mb-4 text-decoration-none">
                                <i class="ti ti-flask text-primary fs-3"></i>
                                <span class="site-logo-text" style="color: var(--bs-primary);">CAPSTONE LAB</span>
                            </a>
                            <h1 class="mb-1 h2">Selamat Datang</h1>
                            <p class="mb-0 text-secondary">
                                Sistem Aset & BHP Laboratorium
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-xl-5 col-lg-6 col-md-8 col-12">
                        <div class="card card-lg mb-6 shadow-sm">
                            <div class="card-body p-6">
                                
                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="ti ti-circle-check fs-5 me-2"></i>
                                        <span>{{ session('success') }}</span>
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

                                <form method="POST" action="{{ route('login') }}" class="needs-validation mb-3">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="signinEmailInput" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="signinEmailInput" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@domain.com" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="formSignUpPassword" class="form-label">Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="formSignUpPassword" name="password" required placeholder="••••••••" />
                                    </div>

                                    <div class="mb-4 d-flex align-items-center justify-content-between">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="rememberMeCheckbox" name="remember" />
                                            <label class="form-check-label" for="rememberMeCheckbox">Ingat Saya</label>
                                        </div>
                                    </div>

                                    <div class="d-grid">
                                        <button class="btn btn-primary py-2" type="submit">Masuk</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Bootstrap 5 JavaScript Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
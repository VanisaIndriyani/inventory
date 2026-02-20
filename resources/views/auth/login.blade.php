<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Monitoring Inventory</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        :root {
            --navy-dark: #0f172a;
            --navy: #1e3a8a;
            --navy-soft: #1d4ed8;
            --bg-soft: #eef2ff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: "Poppins", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at top left, rgba(148, 163, 253, 0.35), transparent 55%),
                        linear-gradient(135deg, var(--navy-dark), var(--navy));
        }

        .auth-shell {
            max-width: 1100px;
            width: 100%;
            padding-inline: 1.25rem;
            padding-block: 1.5rem;
        }

        .auth-card {
            background: rgba(15, 23, 42, 0.09);
            border-radius: 24px;
            padding: 1px;
            box-shadow:
                0 40px 80px rgba(15, 23, 42, 0.75),
                0 0 0 1px rgba(148, 163, 184, 0.25);
            overflow: hidden;
            animation: slideUp 0.6s ease-out forwards;
        }

        .auth-inner {
            display: grid;
            grid-template-columns: minmax(0, 1.15fr) minmax(0, 1fr);
            background: radial-gradient(circle at top left, rgba(248, 250, 252, 0.9), rgba(15, 23, 42, 0.96));
        }

        .auth-branding {
            position: relative;
            padding: 1.75rem 1.75rem 1.75rem 1.9rem;
            color: #e5e7eb;
            background:
                radial-gradient(circle at 0 0, rgba(56, 189, 248, 0.18), transparent 60%),
                radial-gradient(circle at 100% 0, rgba(129, 140, 248, 0.25), transparent 55%),
                linear-gradient(145deg, var(--navy-dark), #020617);
            overflow: hidden;
            backdrop-filter: blur(18px);
        }

        .auth-branding::before {
            content: "";
            position: absolute;
            inset: 8%;
            border-radius: 32px;
            background: radial-gradient(circle at top, rgba(15, 23, 42, 0.3), transparent 55%);
            border: 1px solid rgba(148, 163, 184, 0.16);
            pointer-events: none;
        }

        .auth-branding-inner {
            position: relative;
            z-index: 1;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .branding-center {
            text-align: center;
        }

        .branding-logo-wrapper {
            width: 160px;
            margin: 0 auto 1.4rem;
            padding: .6rem .9rem;
            border-radius: 14px;
            background-color: #ffffff;
            box-shadow: 0 18px 36px rgba(15, 23, 42, 0.55);
        }

        .branding-logo-wrapper img {
            width: 100%;
            height: auto;
            object-fit: contain;
            background-color: transparent;
            display: block;
        }

        .brand-main-title {
            font-size: 1.45rem;
            font-weight: 600;
            margin-bottom: .15rem;
        }

        .brand-tagline {
            font-size: .82rem;
            color: #cbd5f5;
        }

        .auth-form-section {
            background: linear-gradient(145deg, #f9fafb, #e5e7eb);
            padding: 1.9rem 1.9rem 2.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .auth-form-card {
            width: 100%;
            max-width: 380px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid rgba(209, 213, 219, 0.85);
            box-shadow:
                0 20px 50px rgba(15, 23, 42, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.8);
            padding: 1.75rem 1.6rem 1.7rem;
            animation: fadeIn 0.7s ease-out forwards;
        }

        .auth-form-title {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .auth-form-subtitle {
            font-size: .8rem;
            color: #6b7280;
        }

        .form-floating-label {
            font-size: .78rem;
            color: #4b5563;
        }

        .input-wrapper {
            position: relative;
            width: 100%;
        }

        .form-control {
            border-radius: 999px;
            border: 1px solid #e5e7eb;
            padding-left: 2.7rem;
            padding-right: 1rem;
            height: 46px;
            font-size: .85rem;
            transition: border-color .25s ease, box-shadow .25s ease, transform .12s ease;
        }

        .form-control:focus {
            border-color: var(--navy-soft);
            box-shadow: 0 0 0 .18rem rgba(37, 99, 235, 0.25);
            transform: translateY(-1px);
        }

        .input-group-icon {
            position: absolute;
            top: 50%;
            left: .95rem;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: .9rem;
            transition: color .25s ease;
        }

        .form-control:focus + .input-group-icon,
        .input-wrapper:focus-within .input-group-icon {
            color: var(--navy-soft);
        }

        .btn-login {
            border-radius: 999px;
            border: none;
            width: 100%;
            height: 46px;
            background: linear-gradient(135deg, var(--navy), var(--navy-dark));
            color: #f9fafb;
            font-size: .9rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            box-shadow: 0 18px 32px rgba(15, 23, 42, 0.55);
            transition: transform .2s ease, box-shadow .2s ease, filter .2s ease, background-position .2s ease;
            background-size: 150% 150%;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 22px 40px rgba(15, 23, 42, 0.7);
            filter: brightness(1.05);
            background-position: 100% 0;
        }

        .btn-login i {
            font-size: .85rem;
        }

        .login-meta {
            font-size: .74rem;
            color: #6b7280;
        }

        .login-meta strong {
            color: #111827;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(24px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @media (max-width: 992px) {
            .auth-inner {
                grid-template-columns: minmax(0, 1fr);
            }

            .auth-branding {
                padding-bottom: 1.25rem;
            }

            .auth-form-section {
                padding-top: 1.25rem;
            }
        }

        @media (max-width: 576px) {
            .auth-shell {
                padding-inline: .75rem;
            }

            .auth-form-card {
                padding: 1.5rem 1.35rem 1.6rem;
            }
        }
    </style>
</head>
<body>
<div class="auth-shell">
    <div class="auth-card">
        <div class="auth-inner">
            <section class="auth-branding">
                <div class="auth-branding-inner">
                    <div class="branding-center">
                        <div class="branding-logo-wrapper">
                            <img src="{{ asset('img/images.png') }}" alt="Logo">
                        </div>
                        <div class="mb-2">
                            <div class="brand-main-title">Monitoring Inventory System</div>
                            <div class="brand-tagline">General Consumable &amp; Asset Control</div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="auth-form-section">
                <div class="auth-form-card">
                    <div class="mb-3">
                        <h1 class="auth-form-title mb-1">Masuk ke Sistem</h1>
                        <p class="auth-form-subtitle mb-0">
                            Login untuk akses dashboard inventory.
                        </p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger small py-2 mb-3">
                            <i class="fa-solid fa-circle-exclamation me-1"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.perform') }}" autocomplete="off" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label class="form-floating-label mb-1">Email</label>
                            <div class="input-wrapper">
                                <span class="input-group-icon">
                                    <i class="fa-regular fa-envelope"></i>
                                </span>
                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Alamat email"
                                    required
                                    autofocus
                                >
                            </div>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label class="form-floating-label mb-1">Password</label>
                            <div class="input-wrapper">
                                <span class="input-group-icon">
                                    <i class="fa-solid fa-lock"></i>
                                </span>
                                <input
                                    type="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Password"
                                    required
                                >
                            </div>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3 login-meta">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember" style="font-size:.78rem;">
                                    Ingat saya
                                </label>
                            </div>
                         
                        </div>

                        <button type="submit" class="btn-login">
                            <span>Masuk ke Dashboard</span>
                            <i class="fa-solid fa-arrow-right-long"></i>
                        </button>
                    </form>
                </div>
            </section>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Monitoring Inventory')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <style>
        :root {
            --color-navy: #0f172a;
            --color-navy-light: #1e3a8a;
            --color-bg: #f3f4f6;
            --color-border-subtle: #e5e7eb;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Poppins", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background-color: var(--color-bg);
            color: #0f172a;
        }

        .layout {
            min-height: 100vh;
            display: flex;
            background: radial-gradient(circle at top left, rgba(30, 64, 175, 0.12), transparent 55%);
        }

        .sidebar {
            width: 248px;
            background:
                radial-gradient(circle at 0 0, rgba(56, 189, 248, 0.12), transparent 55%),
                radial-gradient(circle at 100% 0, rgba(129, 140, 248, 0.15), transparent 55%),
                linear-gradient(180deg, var(--color-navy), #020617);
            color: #e5e7eb;
            position: fixed;
            inset-block: 0;
            inset-inline-start: 0;
            display: flex;
            flex-direction: column;
            padding: 1.25rem 1.05rem 1.35rem;
            transition: width 0.28s ease, transform 0.28s ease, box-shadow 0.25s ease;
            z-index: 1040;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.9);
        }

        .sidebar-brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            padding-inline: .35rem;
            margin-bottom: 1.8rem;
        }

        .sidebar-brand-icon {
            width: 120px;
            height: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: .45rem .7rem;
            border-radius: 14px;
            background-color: #ffffff;
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.65);
        }

        .sidebar-brand-icon img {
            width: 100%;
            height: auto;
            object-fit: contain;
            display: block;
        }

        .sidebar-brand-text {
            font-weight: 600;
            font-size: .9rem;
            line-height: 1.25;
            text-align: center;
            white-space: normal;
        }

        .sidebar-brand-subtitle {
            font-size: .68rem;
            text-transform: uppercase;
            letter-spacing: .14em;
            color: #94a3b8;
        }

        .sidebar nav {
            flex: 1;
        }

        .sidebar-section-label {
            font-size: .7rem;
            letter-spacing: .09em;
            text-transform: uppercase;
            color: #64748b;
            padding-inline: .65rem;
            margin-bottom: .35rem;
        }

        .sidebar-divider {
            border-top: 1px solid rgba(15, 23, 42, 0.85);
            margin: .6rem 0 1rem;
        }

        .sidebar-pill {
            padding: .25rem .7rem;
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.88);
            border: 1px solid rgba(148, 163, 184, 0.4);
            font-size: .7rem;
            color: #cbd5f5;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            margin-inline: .4rem;
            margin-bottom: .4rem;
        }

        .sidebar-pill i {
            font-size: .8rem;
            color: #38bdf8;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .55rem .85rem;
            margin-bottom: .2rem;
            border-radius: .9rem;
            color: #e5e7eb;
            font-size: .85rem;
            position: relative;
            overflow: hidden;
            transition: background-color .18s ease, color .18s ease, transform .12s ease;
        }

        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
            font-size: .95rem;
        }

        .sidebar .nav-link::before {
            content: "";
            position: absolute;
            inset: 0;
            opacity: 0;
            background: linear-gradient(90deg, rgba(37, 99, 235, 0.35), transparent);
            transition: opacity .2s ease;
        }

        .sidebar .nav-link.active {
            background: linear-gradient(90deg, #1d4ed8, #2563eb);
            color: #eff6ff;
            box-shadow: 0 12px 28px rgba(37, 99, 235, 0.6);
        }

        .sidebar .nav-link.active::before {
            opacity: 1;
        }

        .sidebar .nav-link:not(.active):hover {
            background-color: rgba(15, 23, 42, 0.9);
            transform: translateY(-1px);
        }

        .sidebar-footer {
            margin-top: .75rem;
            padding-inline: .5rem;
            font-size: .7rem;
            color: #6b7280;
        }

        .main-wrapper {
            flex: 1;
            margin-left: 240px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.28s ease;
        }

        .main-navbar {
            position: sticky;
            inset-block-start: 0;
            z-index: 1030;
            backdrop-filter: blur(16px);
            background: linear-gradient(90deg, rgba(15, 23, 42, 0.96), rgba(30, 64, 175, 0.96));
            color: #e5e7eb;
            box-shadow: 0 15px 45px rgba(15, 23, 42, 0.68);
        }

        .main-navbar .navbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .85rem 1.5rem;
        }

        .btn-sidebar-toggle {
            border: none;
            background: rgba(15, 23, 42, 0.5);
            color: #e5e7eb;
            border-radius: .85rem;
            width: 42px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color .18s ease, transform .12s ease;
        }

        .btn-sidebar-toggle:hover {
            background: rgba(15, 23, 42, 0.8);
            transform: translateY(-1px);
        }

        .topbar-title {
            font-size: .9rem;
            font-weight: 500;
            color: #e5e7eb;
        }

        .main-content {
            padding: 1.75rem 1.5rem 2rem;
        }

        .page-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #0f172a;
        }

        .page-subtitle {
            font-size: .85rem;
            color: #6b7280;
        }

        .card-soft {
            border-radius: 1.1rem;
            border: 1px solid rgba(148, 163, 184, 0.25);
            box-shadow:
                0 18px 40px rgba(15, 23, 42, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.6);
        }

        .table-modern {
            border-collapse: separate;
            border-spacing: 0;
            border-radius: .85rem;
            overflow: hidden;
        }

        .table-modern thead tr {
            background: #0f172a;
            color: #e5e7eb;
        }

        .table-modern th,
        .table-modern td {
            vertical-align: middle;
            padding: .65rem .9rem;
            border-bottom: 1px solid rgba(226, 232, 240, 0.85);
        }

        .table-modern tbody tr:hover {
            background: rgba(226, 232, 240, 0.6);
        }

        .badge-status {
            border-radius: 999px;
            font-size: .75rem;
            padding: .15rem .75rem;
            font-weight: 500;
        }

        .badge-status-aman {
            background: rgba(34, 197, 94, 0.12);
            color: #15803d;
        }

        .badge-status-warning {
            background: rgba(234, 179, 8, 0.12);
            color: #b45309;
        }

        .badge-status-reorder {
            background: rgba(239, 68, 68, 0.12);
            color: #b91c1c;
        }

        body.sidebar-collapsed .sidebar {
            width: 80px;
        }

        body.sidebar-collapsed .sidebar-brand-text {
            display: none;
        }

        body.sidebar-collapsed .sidebar-section-label {
            display: none;
        }

        body.sidebar-collapsed .sidebar .nav-link span.nav-label {
            display: none;
        }

        body.sidebar-collapsed .main-wrapper {
            margin-left: 80px;
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            body.sidebar-open .sidebar {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0;
            }

            body.sidebar-collapsed .main-wrapper {
                margin-left: 0;
            }
        }
    </style>

    @yield('styles')
</head>
<body>
<div class="layout">
    @include('partials.sidebar')

    <div class="main-wrapper">
        @include('partials.navbar')

        <main class="main-content">
            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<script>
    (function () {
        const body = document.body;
        const toggleBtn = document.getElementById('sidebarToggle');

        function toggleSidebar() {
            if (window.innerWidth < 992) {
                body.classList.toggle('sidebar-open');
            } else {
                body.classList.toggle('sidebar-collapsed');
            }
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', toggleSidebar);
        }

        window.addEventListener('resize', function () {
            if (window.innerWidth >= 992) {
                body.classList.remove('sidebar-open');
            }
        });
    })();
</script>

@yield('scripts')
</body>
</html>

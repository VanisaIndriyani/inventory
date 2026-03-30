<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('img/images.png') }}" alt="Logo">
        </div>
        <div class="sidebar-brand-text">
            <div>Monitoring Inventory</div>
            <div class="sidebar-brand-subtitle">General Consumable</div>
        </div>
    </div>

    <nav>
        <div class="sidebar-section">
            <div class="sidebar-section-label">Menu Utama</div>
          
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high"></i>
                <span class="nav-label">Dashboard</span>
            </a>
            <a href="{{ route('inventories.index') }}"
               class="nav-link {{ request()->routeIs('inventories.*') ? 'active' : '' }}">
                <i class="fa-solid fa-box-archive"></i>
                <span class="nav-label">Stok Barang</span>
            </a>
            <a href="{{ route('stock-in.index') }}"
               class="nav-link {{ request()->routeIs('stock-in.*') ? 'active' : '' }}">
                <i class="fa-solid fa-arrow-down-long"></i>
                <span class="nav-label">Barang Masuk</span>
            </a>
            <a href="{{ route('stock-out.index') }}"
               class="nav-link {{ request()->routeIs('stock-out.*') ? 'active' : '' }}">
                <i class="fa-solid fa-arrow-up-long"></i>
                <span class="nav-label">Barang Keluar</span>
            </a>
            <a href="{{ route('stock-status.index') }}"
               class="nav-link {{ request()->routeIs('stock-status.*') ? 'active' : '' }}">
                <i class="fa-solid fa-traffic-light"></i>
                <span class="nav-label">Reorder Point</span>
            </a>
            <a href="{{ route('reports.index') }}"
               class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line"></i>
                <span class="nav-label">Laporan</span>
            </a>
        </div>
    </nav>

    <div class="mt-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link w-100 text-start" style="border: none; background: transparent;">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span class="nav-label">Logout</span>
            </button>
        </form>
    </div>

  
</aside>

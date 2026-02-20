<header class="main-navbar">
    <div class="navbar-inner">
        <div class="d-flex align-items-center gap-3">
            <button id="sidebarToggle" class="btn-sidebar-toggle" type="button" aria-label="Toggle sidebar">
                <i class="fa-solid fa-bars-staggered"></i>
            </button>
            <div class="topbar-title d-none d-md-block">
                @yield('page-title', 'Dashboard Monitoring Inventory')
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="d-none d-md-flex flex-column text-end">
                <span style="font-size: .8rem; font-weight: 500;">
                    {{ auth()->user()->name ?? 'User' }}
                </span>
                <span style="font-size: .7rem; color: #9ca3af;">Monitoring Inventory</span>
            </div>
            <div class="dropdown">
                <button class="rounded-circle d-flex align-items-center justify-content-center border-0"
                        type="button"
                        id="userMenuButton"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                        style="width: 34px; height: 34px; background: rgba(15,23,42,0.7); color: #e5e7eb;">
                    <i class="fa-regular fa-user"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenuButton">
                    <li>
                        <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#profileModal">
                            Edit Profil
                        </button>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>

<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title" id="profileModalLabel">Profil Pengguna</h5>
                    <p class="mb-0 small text-muted">
                        Ubah nama, email, atau password akun.
                    </p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('profile.update') }}" class="mb-3">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', auth()->user()->name ?? '') }}"
                               required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email"
                               name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', auth()->user()->email ?? '') }}"
                               required>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Kosongkan jika tidak ingin mengganti">
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password"
                               name="password_confirmation"
                               class="form-control"
                               placeholder="Ulangi password baru">
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                      
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Simpan
                        </button>
                    </div>
                </form>
                <div class="border-top pt-3 mt-2 d-flex justify-content-between align-items-center">
                    <div class="small text-muted">
                        Ingin keluar dari akun?
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

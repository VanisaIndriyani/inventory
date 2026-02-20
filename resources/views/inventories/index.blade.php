@extends('layouts.app')

@section('title', 'Master Inventory')

@section('page-title')
    Master Inventory
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="page-title">Master Inventory</div>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inventoryCreateModal">
            <i class="fa-solid fa-plus me-1"></i> Tambah Barang
        </button>
    </div>

    @if (session('status'))
        <div class="alert alert-success small py-2 mb-3">
            <i class="fa-solid fa-circle-check me-1"></i>
            {{ session('status') }}
        </div>
    @endif

    <div class="card card-soft border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                    <tr>
                        <th>Kode / Nama</th>
                        <th class="text-center">Stok Akhir</th>
                        <th class="text-center">Safety Stock</th>
                        <th>Lokasi</th>
                        <th class="text-center">Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($inventories as $inventory)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $inventory->name }}</div>
                                <div class="small text-muted">{{ $inventory->code }}</div>
                            </td>
                            <td class="text-center fw-semibold">{{ number_format($inventory->final_stock) }}</td>
                            <td class="text-center">{{ number_format($inventory->safety_stock) }}</td>
                            <td>{{ $inventory->storage_location }}</td>
                            <td class="text-center">
                                @php
                                    $statusClass = $inventory->status === 'Aman'
                                        ? 'badge-status-aman'
                                        : ($inventory->status === 'Warning' ? 'badge-status-warning' : 'badge-status-reorder');
                                @endphp
                                <span class="badge-status {{ $statusClass }}">{{ $inventory->status }}</span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button"
                                            class="btn btn-outline-secondary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#inventoryShowModal-{{ $inventory->id }}">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#inventoryEditModal-{{ $inventory->id }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <form action="{{ route('inventories.destroy', $inventory) }}" method="POST"
                                          onsubmit="return confirm('Hapus data barang ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">
                                Belum ada data inventory.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="inventoryCreateModal" tabindex="-1" aria-labelledby="inventoryCreateModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h5 class="modal-title" id="inventoryCreateModalLabel">Tambah Barang</h5>
                        <p class="mb-0 small text-muted">
                            Input data master barang baru untuk dimonitor di sistem.
                        </p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-2">
                    <form method="POST" action="{{ route('inventories.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Kode Barang</label>
                                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                                       value="{{ old('code') }}" required>
                                @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Nama Barang</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Stok Awal</label>
                                <input type="number" min="0" name="initial_stock"
                                       class="form-control @error('initial_stock') is-invalid @enderror"
                                       value="{{ old('initial_stock', 0) }}" required>
                                @error('initial_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Safety Stock</label>
                                <input type="number" min="0" name="safety_stock"
                                       class="form-control @error('safety_stock') is-invalid @enderror"
                                       value="{{ old('safety_stock', 0) }}" required>
                                @error('safety_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Lead Time Supplier (hari)</label>
                                <input type="number" min="0" name="supplier_lead_time"
                                       class="form-control @error('supplier_lead_time') is-invalid @enderror"
                                       value="{{ old('supplier_lead_time', 0) }}" required>
                                @error('supplier_lead_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Supplier Utama</label>
                                <input type="text" name="main_supplier"
                                       class="form-control @error('main_supplier') is-invalid @enderror"
                                       value="{{ old('main_supplier') }}">
                                @error('main_supplier')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Lokasi Penyimpanan</label>
                                <input type="text" name="storage_location"
                                       class="form-control @error('storage_location') is-invalid @enderror"
                                       value="{{ old('storage_location') }}">
                                @error('storage_location')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-4 d-flex justify-content-end gap-2">
                          
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @foreach($inventories as $inventory)
        <div class="modal fade" id="inventoryShowModal-{{ $inventory->id }}" tabindex="-1"
             aria-labelledby="inventoryShowModalLabel-{{ $inventory->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <div>
                            <h5 class="modal-title" id="inventoryShowModalLabel-{{ $inventory->id }}">Detail Barang</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-2">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="small text-muted">Nama Barang</div>
                                <div class="fw-semibold">{{ $inventory->name }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-muted">Kode Barang</div>
                                <div class="fw-semibold">{{ $inventory->code }}</div>
                            </div>
                            <div class="col-md-3">
                                <div class="small text-muted">Stok Awal</div>
                                <div>{{ number_format($inventory->initial_stock) }}</div>
                            </div>
                            <div class="col-md-3">
                                <div class="small text-muted">Masuk</div>
                                <div>{{ number_format($inventory->total_in) }}</div>
                            </div>
                            <div class="col-md-3">
                                <div class="small text-muted">Keluar</div>
                                <div>{{ number_format($inventory->total_out) }}</div>
                            </div>
                            <div class="col-md-3">
                                <div class="small text-muted">Stok Akhir</div>
                                <div class="fw-semibold">{{ number_format($inventory->final_stock) }}</div>
                            </div>
                            <div class="col-md-3">
                                <div class="small text-muted">Safety Stock</div>
                                <div>{{ number_format($inventory->safety_stock) }}</div>
                            </div>
                            <div class="col-md-3">
                                <div class="small text-muted">Reorder Point</div>
                                <div>{{ number_format($inventory->reorder_point) }}</div>
                            </div>
                            <div class="col-md-3">
                                <div class="small text-muted">Lead Time Supplier</div>
                                <div>{{ $inventory->supplier_lead_time }} hari</div>
                            </div>
                            <div class="col-md-3">
                                <div class="small text-muted">Status</div>
                                @php
                                    $statusClass = $inventory->status === 'Aman'
                                        ? 'badge-status-aman'
                                        : ($inventory->status === 'Warning' ? 'badge-status-warning' : 'badge-status-reorder');
                                @endphp
                                <span class="badge-status {{ $statusClass }}">{{ $inventory->status }}</span>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-muted">Supplier Utama</div>
                                <div>{{ $inventory->main_supplier }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-muted">Lokasi Penyimpanan</div>
                                <div>{{ $inventory->storage_location }}</div>
                            </div>
                        </div>
                        <div class="mt-4 d-flex justify-content-end">
                          
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="inventoryEditModal-{{ $inventory->id }}" tabindex="-1"
             aria-labelledby="inventoryEditModalLabel-{{ $inventory->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <div>
                            <h5 class="modal-title" id="inventoryEditModalLabel-{{ $inventory->id }}">Edit Barang</h5>
                            <p class="mb-0 small text-muted">
                                Perbarui informasi master data barang.
                            </p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-2">
                        <form method="POST" action="{{ route('inventories.update', $inventory) }}">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Kode Barang</label>
                                    <input type="text" name="code"
                                           class="form-control"
                                           value="{{ $inventory->code }}" required>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">Nama Barang</label>
                                    <input type="text" name="name"
                                           class="form-control"
                                           value="{{ $inventory->name }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Stok Awal</label>
                                    <input type="number" min="0" name="initial_stock"
                                           class="form-control"
                                           value="{{ $inventory->initial_stock }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Safety Stock</label>
                                    <input type="number" min="0" name="safety_stock"
                                           class="form-control"
                                           value="{{ $inventory->safety_stock }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Lead Time Supplier (hari)</label>
                                    <input type="number" min="0" name="supplier_lead_time"
                                           class="form-control"
                                           value="{{ $inventory->supplier_lead_time }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Supplier Utama</label>
                                    <input type="text" name="main_supplier"
                                           class="form-control"
                                           value="{{ $inventory->main_supplier }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Lokasi Penyimpanan</label>
                                    <input type="text" name="storage_location"
                                           class="form-control"
                                           value="{{ $inventory->storage_location }}">
                                </div>
                            </div>
                            <div class="mt-4 d-flex justify-content-end gap-2">
                               
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('scripts')
    @parent
@endsection

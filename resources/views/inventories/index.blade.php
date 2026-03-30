@extends('layouts.app')

@section('title', 'Stok Barang')

@section('page-title')
    Stok Barang
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="page-title">Stok Barang</div>
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
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th class="text-center">Jan</th>
                        <th class="text-center">Feb</th>
                        <th class="text-center">Mar</th>
                        <th class="text-center">Apr</th>
                        <th class="text-center">Mei</th>
                        <th class="text-center">Jun</th>
                        <th class="text-center">Jul</th>
                        <th class="text-center">Agu</th>
                        <th class="text-center">Sep</th>
                        <th class="text-center">Okt</th>
                        <th class="text-center">Nov</th>
                        <th class="text-center">Des</th>
                        <th>Lokasi</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($inventories as $inventory)
                        <tr>
                            <td class="fw-semibold">{{ $inventory->code }}</td>
                            <td>{{ $inventory->name }}</td>
                            <td class="text-center">{{ number_format($inventory->stock_jan) }}</td>
                            <td class="text-center">{{ number_format($inventory->stock_feb) }}</td>
                            <td class="text-center">{{ number_format($inventory->stock_mar) }}</td>
                            <td class="text-center">{{ number_format($inventory->stock_apr) }}</td>
                            <td class="text-center">{{ number_format($inventory->stock_may) }}</td>
                            <td class="text-center">{{ number_format($inventory->stock_jun) }}</td>
                            <td class="text-center">{{ number_format($inventory->stock_jul) }}</td>
                            <td class="text-center">{{ number_format($inventory->stock_aug) }}</td>
                            <td class="text-center">{{ number_format($inventory->stock_sep) }}</td>
                            <td class="text-center">{{ number_format($inventory->stock_oct) }}</td>
                            <td class="text-center">{{ number_format($inventory->stock_nov) }}</td>
                            <td class="text-center">{{ number_format($inventory->stock_dec) }}</td>
                            <td>{{ $inventory->storage_location }}</td>
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
                            <td colspan="16" class="text-center text-muted py-3">
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
                            <div class="col-md-6">
                                <label class="form-label">Lokasi</label>
                                <input type="text" name="storage_location"
                                       class="form-control @error('storage_location') is-invalid @enderror"
                                       value="{{ old('storage_location') }}">
                                @error('storage_location')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <div class="fw-semibold mb-2">Stok per Bulan (Januari - Desember)</div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Jan</label>
                                <input type="number" min="0" name="stock_jan"
                                       class="form-control @error('stock_jan') is-invalid @enderror"
                                       value="{{ old('stock_jan', 0) }}" required>
                                @error('stock_jan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Feb</label>
                                <input type="number" min="0" name="stock_feb"
                                       class="form-control @error('stock_feb') is-invalid @enderror"
                                       value="{{ old('stock_feb', 0) }}" required>
                                @error('stock_feb')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Mar</label>
                                <input type="number" min="0" name="stock_mar"
                                       class="form-control @error('stock_mar') is-invalid @enderror"
                                       value="{{ old('stock_mar', 0) }}" required>
                                @error('stock_mar')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Apr</label>
                                <input type="number" min="0" name="stock_apr"
                                       class="form-control @error('stock_apr') is-invalid @enderror"
                                       value="{{ old('stock_apr', 0) }}" required>
                                @error('stock_apr')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Mei</label>
                                <input type="number" min="0" name="stock_may"
                                       class="form-control @error('stock_may') is-invalid @enderror"
                                       value="{{ old('stock_may', 0) }}" required>
                                @error('stock_may')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Jun</label>
                                <input type="number" min="0" name="stock_jun"
                                       class="form-control @error('stock_jun') is-invalid @enderror"
                                       value="{{ old('stock_jun', 0) }}" required>
                                @error('stock_jun')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Jul</label>
                                <input type="number" min="0" name="stock_jul"
                                       class="form-control @error('stock_jul') is-invalid @enderror"
                                       value="{{ old('stock_jul', 0) }}" required>
                                @error('stock_jul')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Agu</label>
                                <input type="number" min="0" name="stock_aug"
                                       class="form-control @error('stock_aug') is-invalid @enderror"
                                       value="{{ old('stock_aug', 0) }}" required>
                                @error('stock_aug')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Sep</label>
                                <input type="number" min="0" name="stock_sep"
                                       class="form-control @error('stock_sep') is-invalid @enderror"
                                       value="{{ old('stock_sep', 0) }}" required>
                                @error('stock_sep')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Okt</label>
                                <input type="number" min="0" name="stock_oct"
                                       class="form-control @error('stock_oct') is-invalid @enderror"
                                       value="{{ old('stock_oct', 0) }}" required>
                                @error('stock_oct')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Nov</label>
                                <input type="number" min="0" name="stock_nov"
                                       class="form-control @error('stock_nov') is-invalid @enderror"
                                       value="{{ old('stock_nov', 0) }}" required>
                                @error('stock_nov')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Des</label>
                                <input type="number" min="0" name="stock_dec"
                                       class="form-control @error('stock_dec') is-invalid @enderror"
                                       value="{{ old('stock_dec', 0) }}" required>
                                @error('stock_dec')
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
                            <div class="col-12">
                                <div class="small text-muted">Lokasi</div>
                                <div class="fw-semibold">{{ $inventory->storage_location }}</div>
                            </div>
                            <div class="col-12">
                                <div class="small text-muted mb-2">Stok per Bulan</div>
                                <div class="table-responsive">
                                    <table class="table table-sm table-modern mb-0">
                                        <thead>
                                        <tr>
                                            <th class="text-center">Jan</th>
                                            <th class="text-center">Feb</th>
                                            <th class="text-center">Mar</th>
                                            <th class="text-center">Apr</th>
                                            <th class="text-center">Mei</th>
                                            <th class="text-center">Jun</th>
                                            <th class="text-center">Jul</th>
                                            <th class="text-center">Agu</th>
                                            <th class="text-center">Sep</th>
                                            <th class="text-center">Okt</th>
                                            <th class="text-center">Nov</th>
                                            <th class="text-center">Des</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="text-center">{{ number_format($inventory->stock_jan) }}</td>
                                            <td class="text-center">{{ number_format($inventory->stock_feb) }}</td>
                                            <td class="text-center">{{ number_format($inventory->stock_mar) }}</td>
                                            <td class="text-center">{{ number_format($inventory->stock_apr) }}</td>
                                            <td class="text-center">{{ number_format($inventory->stock_may) }}</td>
                                            <td class="text-center">{{ number_format($inventory->stock_jun) }}</td>
                                            <td class="text-center">{{ number_format($inventory->stock_jul) }}</td>
                                            <td class="text-center">{{ number_format($inventory->stock_aug) }}</td>
                                            <td class="text-center">{{ number_format($inventory->stock_sep) }}</td>
                                            <td class="text-center">{{ number_format($inventory->stock_oct) }}</td>
                                            <td class="text-center">{{ number_format($inventory->stock_nov) }}</td>
                                            <td class="text-center">{{ number_format($inventory->stock_dec) }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
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
                                <div class="col-md-6">
                                    <label class="form-label">Lokasi</label>
                                    <input type="text" name="storage_location"
                                           class="form-control"
                                           value="{{ $inventory->storage_location }}">
                                </div>
                                <div class="col-12">
                                    <div class="fw-semibold mb-2">Stok per Bulan (Januari - Desember)</div>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Jan</label>
                                    <input type="number" min="0" name="stock_jan" class="form-control"
                                           value="{{ $inventory->stock_jan }}" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Feb</label>
                                    <input type="number" min="0" name="stock_feb" class="form-control"
                                           value="{{ $inventory->stock_feb }}" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Mar</label>
                                    <input type="number" min="0" name="stock_mar" class="form-control"
                                           value="{{ $inventory->stock_mar }}" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Apr</label>
                                    <input type="number" min="0" name="stock_apr" class="form-control"
                                           value="{{ $inventory->stock_apr }}" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Mei</label>
                                    <input type="number" min="0" name="stock_may" class="form-control"
                                           value="{{ $inventory->stock_may }}" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Jun</label>
                                    <input type="number" min="0" name="stock_jun" class="form-control"
                                           value="{{ $inventory->stock_jun }}" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Jul</label>
                                    <input type="number" min="0" name="stock_jul" class="form-control"
                                           value="{{ $inventory->stock_jul }}" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Agu</label>
                                    <input type="number" min="0" name="stock_aug" class="form-control"
                                           value="{{ $inventory->stock_aug }}" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Sep</label>
                                    <input type="number" min="0" name="stock_sep" class="form-control"
                                           value="{{ $inventory->stock_sep }}" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Okt</label>
                                    <input type="number" min="0" name="stock_oct" class="form-control"
                                           value="{{ $inventory->stock_oct }}" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Nov</label>
                                    <input type="number" min="0" name="stock_nov" class="form-control"
                                           value="{{ $inventory->stock_nov }}" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Des</label>
                                    <input type="number" min="0" name="stock_dec" class="form-control"
                                           value="{{ $inventory->stock_dec }}" required>
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

@extends('layouts.app')

@section('title', 'Edit Stok Barang')

@section('page-title')
    Edit Stok Barang
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="page-title">Edit Stok Barang</div>
        </div>
        <a href="{{ route('inventories.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left-long me-1"></i> Kembali
        </a>
    </div>

    <div class="card card-soft border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('inventories.update', $inventory) }}">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Kode Barang</label>
                        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                               value="{{ old('code', $inventory->code) }}" required>
                        @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $inventory->name) }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Stok Awal</label>
                        <input type="number" min="0" name="initial_stock" class="form-control @error('initial_stock') is-invalid @enderror"
                               value="{{ old('initial_stock', $inventory->initial_stock) }}" required>
                        @error('initial_stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Lokasi</label>
                        <input type="text" name="storage_location"
                               class="form-control @error('storage_location') is-invalid @enderror"
                               value="{{ old('storage_location', $inventory->storage_location) }}">
                        @error('storage_location')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <div class="fw-semibold mb-2">Stok per Bulan (Januari - Desember)</div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Jan</label>
                        <input type="number" min="0" name="stock_jan" class="form-control @error('stock_jan') is-invalid @enderror"
                               value="{{ old('stock_jan', $inventory->stock_jan) }}" required>
                        @error('stock_jan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Feb</label>
                        <input type="number" min="0" name="stock_feb" class="form-control @error('stock_feb') is-invalid @enderror"
                               value="{{ old('stock_feb', $inventory->stock_feb) }}" required>
                        @error('stock_feb')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Mar</label>
                        <input type="number" min="0" name="stock_mar" class="form-control @error('stock_mar') is-invalid @enderror"
                               value="{{ old('stock_mar', $inventory->stock_mar) }}" required>
                        @error('stock_mar')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Apr</label>
                        <input type="number" min="0" name="stock_apr" class="form-control @error('stock_apr') is-invalid @enderror"
                               value="{{ old('stock_apr', $inventory->stock_apr) }}" required>
                        @error('stock_apr')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Mei</label>
                        <input type="number" min="0" name="stock_may" class="form-control @error('stock_may') is-invalid @enderror"
                               value="{{ old('stock_may', $inventory->stock_may) }}" required>
                        @error('stock_may')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Jun</label>
                        <input type="number" min="0" name="stock_jun" class="form-control @error('stock_jun') is-invalid @enderror"
                               value="{{ old('stock_jun', $inventory->stock_jun) }}" required>
                        @error('stock_jun')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Jul</label>
                        <input type="number" min="0" name="stock_jul" class="form-control @error('stock_jul') is-invalid @enderror"
                               value="{{ old('stock_jul', $inventory->stock_jul) }}" required>
                        @error('stock_jul')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Agu</label>
                        <input type="number" min="0" name="stock_aug" class="form-control @error('stock_aug') is-invalid @enderror"
                               value="{{ old('stock_aug', $inventory->stock_aug) }}" required>
                        @error('stock_aug')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Sep</label>
                        <input type="number" min="0" name="stock_sep" class="form-control @error('stock_sep') is-invalid @enderror"
                               value="{{ old('stock_sep', $inventory->stock_sep) }}" required>
                        @error('stock_sep')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Okt</label>
                        <input type="number" min="0" name="stock_oct" class="form-control @error('stock_oct') is-invalid @enderror"
                               value="{{ old('stock_oct', $inventory->stock_oct) }}" required>
                        @error('stock_oct')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Nov</label>
                        <input type="number" min="0" name="stock_nov" class="form-control @error('stock_nov') is-invalid @enderror"
                               value="{{ old('stock_nov', $inventory->stock_nov) }}" required>
                        @error('stock_nov')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Des</label>
                        <input type="number" min="0" name="stock_dec" class="form-control @error('stock_dec') is-invalid @enderror"
                               value="{{ old('stock_dec', $inventory->stock_dec) }}" required>
                        @error('stock_dec')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
@endsection

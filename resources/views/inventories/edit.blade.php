@extends('layouts.app')

@section('title', 'Edit Inventory')

@section('page-title')
    Edit Inventory
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="page-title">Edit Barang</div>
            <div class="page-subtitle">
                Perbarui informasi master data barang.
            </div>
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
                    <div class="col-md-3">
                        <label class="form-label">Stok Awal</label>
                        <input type="number" min="0" name="initial_stock"
                               class="form-control @error('initial_stock') is-invalid @enderror"
                               value="{{ old('initial_stock', $inventory->initial_stock) }}" required>
                        @error('initial_stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Safety Stock</label>
                        <input type="number" min="0" name="safety_stock"
                               class="form-control @error('safety_stock') is-invalid @enderror"
                               value="{{ old('safety_stock', $inventory->safety_stock) }}" required>
                        @error('safety_stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Lead Time Supplier (hari)</label>
                        <input type="number" min="0" name="supplier_lead_time"
                               class="form-control @error('supplier_lead_time') is-invalid @enderror"
                               value="{{ old('supplier_lead_time', $inventory->supplier_lead_time) }}" required>
                        @error('supplier_lead_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Supplier Utama</label>
                        <input type="text" name="main_supplier"
                               class="form-control @error('main_supplier') is-invalid @enderror"
                               value="{{ old('main_supplier', $inventory->main_supplier) }}">
                        @error('main_supplier')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Lokasi Penyimpanan</label>
                        <input type="text" name="storage_location"
                               class="form-control @error('storage_location') is-invalid @enderror"
                               value="{{ old('storage_location', $inventory->storage_location) }}">
                        @error('storage_location')
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


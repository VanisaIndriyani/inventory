@extends('layouts.app')

@section('title', 'Barang Masuk')

@section('page-title')
    Barang Masuk
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="page-title">Barang Masuk</div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#stockInCreateModal">
            <i class="fa-solid fa-plus me-1"></i> Penerimaan Baru
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
                        <th>Tanggal</th>
                        <th>Barang</th>
                        <th class="text-center">Jumlah</th>
                        <th>Vendor</th>
                        <th>Penerima</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($stockIns as $row)
                        <tr>
                            <td>{{ $row->received_at?->format('d/m/Y') }}</td>
                            <td>
                                <div class="fw-semibold">{{ $row->inventory->name ?? '-' }}</div>
                                <div class="small text-muted">{{ $row->inventory->code ?? '' }}</div>
                            </td>
                            <td class="text-center fw-semibold text-success">
                                +{{ number_format($row->quantity) }}
                            </td>
                            <td>{{ $row->supplier ?: $row->inventory->main_supplier }}</td>
                            <td>{{ $row->received_by ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                Belum ada transaksi barang masuk.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $stockIns->links() }}
            </div>
        </div>
    </div>

    <div class="modal fade" id="stockInCreateModal" tabindex="-1" aria-labelledby="stockInCreateModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h5 class="modal-title" id="stockInCreateModalLabel">Penerimaan Barang</h5>
                        <p class="mb-0 small text-muted">
                            Catat barang masuk untuk menambah stok otomatis.
                        </p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-2">
                    <form method="POST" action="{{ route('stock-in.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Penerimaan</label>
                                <input type="date" name="received_at"
                                       class="form-control @error('received_at') is-invalid @enderror"
                                       value="{{ old('received_at', now()->toDateString()) }}" required>
                                @error('received_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Barang</label>
                                <select name="inventory_id"
                                        class="form-select @error('inventory_id') is-invalid @enderror" required>
                                    <option value="">Pilih barang</option>
                                    @foreach($inventories as $inventory)
                                        <option value="{{ $inventory->id }}"
                                            {{ old('inventory_id') == $inventory->id ? 'selected' : '' }}>
                                            {{ $inventory->code }} - {{ $inventory->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('inventory_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Jumlah Masuk</label>
                                <input type="number" min="1" name="quantity"
                                       class="form-control @error('quantity') is-invalid @enderror"
                                       value="{{ old('quantity', 1) }}" required>
                                @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Vendor</label>
                                <input type="text" name="supplier"
                                       class="form-control @error('supplier') is-invalid @enderror"
                                       value="{{ old('supplier') }}"
                                       placeholder="Nama vendor">
                                @error('supplier')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Penerima Barang</label>
                                <input type="text" name="received_by"
                                       class="form-control @error('received_by') is-invalid @enderror"
                                       value="{{ old('received_by') }}">
                                @error('received_by')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
@endsection

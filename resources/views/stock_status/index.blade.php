@extends('layouts.app')

@section('title', 'Status Stok')

@section('page-title')
    Status Stok
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="page-title">Status Stok</div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('stock-status.index') }}"
               class="btn btn-sm {{ $statusFilter ? 'btn-outline-secondary' : 'btn-primary' }}">
                Semua
            </a>
            <a href="{{ route('stock-status.index', ['status' => 'Aman']) }}"
               class="btn btn-sm {{ $statusFilter === 'Aman' ? 'btn-success' : 'btn-outline-success' }}">
                Aman
            </a>
            <a href="{{ route('stock-status.index', ['status' => 'Warning']) }}"
               class="btn btn-sm {{ $statusFilter === 'Warning' ? 'btn-warning text-dark' : 'btn-outline-warning' }}">
                Warning
            </a>
            <a href="{{ route('stock-status.index', ['status' => 'Reorder']) }}"
               class="btn btn-sm {{ $statusFilter === 'Reorder' ? 'btn-danger' : 'btn-outline-danger' }}">
                Reorder
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card card-soft border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted text-uppercase small">Aman</span>
                        <span class="badge-status badge-status-aman">Aman</span>
                    </div>
                    <div class="h3 mb-0">{{ number_format($grouped['Aman']->count()) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-soft border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted text-uppercase small">Warning</span>
                        <span class="badge-status badge-status-warning">Warning</span>
                    </div>
                    <div class="h3 mb-0">{{ number_format($grouped['Warning']->count()) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-soft border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted text-uppercase small">Reorder</span>
                        <span class="badge-status badge-status-reorder">Reorder</span>
                    </div>
                    <div class="h3 mb-0">{{ number_format($grouped['Reorder']->count()) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-soft border-0">
        <div class="card-body">
            <div class="fw-semibold mb-3">Daftar Barang Berdasarkan Status</div>
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                    <tr>
                        <th>Barang</th>
                        <th class="text-end">Stok Awal</th>
                        <th class="text-end">Masuk</th>
                        <th class="text-end">Keluar</th>
                        <th class="text-end">Stok Akhir</th>
                        <th class="text-center">Safety Stock</th>
                        <th class="text-center">Reorder Point</th>
                        <th class="text-center">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $sorted = $inventories->sortBy('final_stock');
                    @endphp
                    @forelse($sorted as $inventory)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $inventory->name }}</div>
                                <div class="small text-muted">{{ $inventory->code }}</div>
                            </td>
                            <td class="text-end">{{ number_format($inventory->initial_stock) }}</td>
                            <td class="text-end">{{ number_format($inventory->total_in) }}</td>
                            <td class="text-end">{{ number_format($inventory->total_out) }}</td>
                            <td class="text-end fw-semibold">{{ number_format($inventory->final_stock) }}</td>
                            <td class="text-center">{{ number_format($inventory->safety_stock) }}</td>
                            <td class="text-center">{{ number_format($inventory->reorder_point) }}</td>
                            <td class="text-center">
                                @php
                                    $statusClass = $inventory->status === 'Aman'
                                        ? 'badge-status-aman'
                                        : ($inventory->status === 'Warning' ? 'badge-status-warning' : 'badge-status-reorder');
                                @endphp
                                <span class="badge-status {{ $statusClass }}">{{ $inventory->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-3">
                                Belum ada data inventory.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

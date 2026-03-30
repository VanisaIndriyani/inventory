@extends('layouts.app')

@section('title', 'Reorder Point')

@section('page-title')
    Reorder Point
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="page-title">Reorder Point</div>
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

    @if (session('status'))
        <div class="alert alert-success small py-2 mb-3">
            <i class="fa-solid fa-circle-check me-1"></i>
            {{ session('status') }}
        </div>
    @endif

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
            <div class="fw-semibold mb-3">Daftar Reorder Point</div>
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                    <tr>
                        <th>Barang</th>
                        <th class="text-end">Stok Bulan Ini</th>
                        <th class="text-end">Usage Rate</th>
                        <th class="text-end">Lead Time</th>
                        <th class="text-center">Safety Stock</th>
                        <th class="text-center">ROP Alert</th>
                        <th class="text-center">ROP</th>
                        <th class="text-center">Status</th>
                        <th class="text-end">Aksi</th>
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
                            <td class="text-end fw-semibold">{{ number_format($inventory->final_stock) }}</td>
                            <td class="text-end">{{ number_format((float) $inventory->usage_rate, 2) }}</td>
                            <td class="text-end">{{ number_format($inventory->lead_time) }}</td>
                            <td class="text-center">{{ number_format($inventory->safety_stock) }}</td>
                            <td class="text-center">{{ number_format($inventory->rop_alert) }}</td>
                            <td class="text-center">{{ number_format($inventory->reorder_point) }}</td>
                            <td class="text-center">
                                @php
                                    $statusClass = $inventory->status === 'Aman'
                                        ? 'badge-status-aman'
                                        : ($inventory->status === 'Warning' ? 'badge-status-warning' : 'badge-status-reorder');
                                @endphp
                                <span class="badge-status {{ $statusClass }}">{{ $inventory->status }}</span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#ropEditModal-{{ $inventory->id }}">
                                    <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-3">
                                Belum ada data inventory.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @foreach($inventories as $inventory)
        <div class="modal fade" id="ropEditModal-{{ $inventory->id }}" tabindex="-1"
             aria-labelledby="ropEditModalLabel-{{ $inventory->id }}" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <div>
                            <h5 class="modal-title" id="ropEditModalLabel-{{ $inventory->id }}">Edit Reorder Point</h5>
                            <p class="mb-0 small text-muted">{{ $inventory->code }} - {{ $inventory->name }}</p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-2">
                        <form method="POST" action="{{ route('stock-status.update', $inventory) }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="{{ $statusFilter }}">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Usage Rate</label>
                                    <input type="number" min="0" step="0.01" name="usage_rate" class="form-control"
                                           value="{{ old('usage_rate', (float) $inventory->usage_rate) }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Lead Time (hari)</label>
                                    <input type="number" min="0" name="lead_time" class="form-control"
                                           value="{{ old('lead_time', $inventory->lead_time) }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Safety Stock</label>
                                    <input type="number" min="0" name="safety_stock" class="form-control"
                                           value="{{ old('safety_stock', $inventory->safety_stock) }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">ROP Alert</label>
                                    <input type="number" min="0" name="rop_alert" class="form-control"
                                           value="{{ old('rop_alert', $inventory->rop_alert) }}" required>
                                </div>

                                <div class="col-12 mt-2">
                                    <div class="fw-semibold">Hari 1 - 30 (Diisi Manual)</div>
                                </div>

                                @for($day = 1; $day <= 30; $day++)
                                    <div class="col-md-2">
                                        <label class="form-label">{{ $day }}</label>
                                        <input type="number" min="0" name="day_{{ $day }}" class="form-control"
                                               value="{{ old('day_' . $day, (int) ($inventory->{'day_' . $day} ?? 0)) }}" required>
                                    </div>
                                @endfor
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
    @endforeach
@endsection

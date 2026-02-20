@extends('layouts.app')

@section('title', 'Dashboard Monitoring Inventory')

@section('page-title')
    Dashboard Monitoring Inventory
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="page-title">Dashboard</div>
        </div>
        <form method="GET" class="d-flex gap-2 align-items-end">
            <div>
                <input type="date" name="from_date" value="{{ $fromDate }}" class="form-control form-control-sm" aria-label="Dari tanggal">
            </div>
            <div>
                <input type="date" name="to_date" value="{{ $toDate }}" class="form-control form-control-sm" aria-label="Sampai tanggal">
            </div>
            <button type="submit" class="btn btn-sm btn-outline-light ms-1" style="margin-bottom: 2px;">
                <i class="fa-solid fa-filter me-1"></i>
            </button>
        </form>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card card-soft border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted text-uppercase small">Total Item</span>
                        <i class="fa-solid fa-box-archive text-primary"></i>
                    </div>
                    <div class="h3 mb-0">{{ number_format($totalItems) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-soft border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted text-uppercase small">Total Stok</span>
                        <i class="fa-solid fa-layer-group text-success"></i>
                    </div>
                    <div class="h3 mb-0">{{ number_format($totalStock) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-soft border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted text-uppercase small">Warning</span>
                        <span class="badge-status badge-status-warning px-2 py-1">!</span>
                    </div>
                    <div class="h3 mb-0">{{ number_format($warningCount) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-soft border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted text-uppercase small">Reorder</span>
                        <span class="badge-status badge-status-reorder px-2 py-1">!</span>
                    </div>
                    <div class="h3 mb-0">{{ number_format($reorderCount) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-7">
            <div class="card card-soft border-0 h-100">
                <div class="card-body">
                    <div class="fw-semibold mb-3">Pergerakan Stok</div>
                    <div style="height:300px;">
                        <canvas id="movementChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card card-soft border-0 h-100">
                <div class="card-body">
                    <div class="fw-semibold mb-3">Barang Kritis</div>
                    <div class="table-responsive">
                        <table class="table table-modern mb-0">
                            <thead>
                            <tr>
                                <th>Barang</th>
                                <th class="text-end">Stok Akhir</th>
                                <th class="text-center">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($criticalItems as $item)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $item->name }}</div>
                                        <div class="small text-muted">{{ $item->code }}</div>
                                    </td>
                                    <td class="text-end">{{ number_format($item->final_stock) }}</td>
                                    <td class="text-center">
                                        @php
                                            $statusClass = $item->status === 'Aman'
                                                ? 'badge-status-aman'
                                                : ($item->status === 'Warning' ? 'badge-status-warning' : 'badge-status-reorder');
                                        @endphp
                                        <span class="badge-status {{ $statusClass }}">{{ $item->status }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted small py-3">
                                        Tidak ada barang dengan status Warning/Reorder.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-soft border-0">
        <div class="card-body">
            <div class="fw-semibold mb-3">Ringkasan Stok per Barang</div>
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
                    @forelse($inventories as $inventory)
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

@section('scripts')
    <script>
        (function () {
            const ctx = document.getElementById('movementChart');
            if (!ctx) {
                return;
            }

            const labels = {!! json_encode($chartLabels) !!};
            const incoming = {!! json_encode($chartIncoming) !!};
            const outgoing = {!! json_encode($chartOutgoing) !!};

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Barang Masuk',
                            data: incoming,
                            backgroundColor: 'rgba(34, 197, 94, 0.7)',
                            borderColor: '#16a34a',
                            borderWidth: 1,
                        },
                        {
                            label: 'Barang Keluar',
                            data: outgoing,
                            backgroundColor: 'rgba(239, 68, 68, 0.75)',
                            borderColor: '#b91c1c',
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                        },
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                            },
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(148, 163, 184, 0.25)',
                            },
                        },
                    },
                },
            });
        })();
    </script>
@endsection

@extends('layouts.app')

@section('title', 'Laporan Stok')

@section('page-title')
    Laporan Stok Periodik
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="page-title">Laporan Stok</div>
        <div class="d-flex flex-wrap gap-2 align-items-end">
            <form method="GET" class="d-flex flex-wrap gap-2 align-items-end">
                <div>
                    <input type="date" name="from_date" value="{{ $fromDate }}" class="form-control form-control-sm">
                </div>
                <div>
                    <input type="date" name="to_date" value="{{ $toDate }}" class="form-control form-control-sm">
                </div>
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fa-solid fa-filter me-1"></i> Terapkan
                </button>
            </form>
            <form method="GET" action="{{ route('reports.exportPdf') }}">
                <input type="hidden" name="from_date" value="{{ $fromDate }}">
                <input type="hidden" name="to_date" value="{{ $toDate }}">
                <button type="submit" class="btn btn-sm btn-danger">
                    <i class="fa-solid fa-file-pdf me-1"></i> Export PDF
                </button>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-7">
            <div class="card card-soft border-0 h-100">
                <div class="card-body">
                    <div class="fw-semibold mb-3">Ringkasan Stok per Barang</div>
                    <div class="table-responsive">
                        <table class="table table-modern mb-0">
                            <thead>
                            <tr>
                                <th>Barang</th>
                                <th class="text-center">Stok Awal</th>
                                <th class="text-center">Masuk</th>
                                <th class="text-center">Keluar</th>
                                <th class="text-center">Stok Akhir</th>
                                <th class="text-center">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($report as $row)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $row['inventory']->name }}</div>
                                        <div class="small text-muted">{{ $row['inventory']->code }}</div>
                                    </td>
                                    <td class="text-center">{{ number_format($row['inventory']->initial_stock) }}</td>
                                    <td class="text-center text-success">+{{ number_format($row['total_in']) }}</td>
                                    <td class="text-center text-danger">-{{ number_format($row['total_out']) }}</td>
                                    <td class="text-center fw-semibold">{{ number_format($row['final_stock']) }}</td>
                                    <td class="text-center">
                                        @php
                                            $statusClass = $row['status'] === 'Aman'
                                                ? 'badge-status-aman'
                                                : ($row['status'] === 'Warning' ? 'badge-status-warning' : 'badge-status-reorder');
                                        @endphp
                                        <span class="badge-status {{ $statusClass }}">{{ $row['status'] }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">
                                        Belum ada data untuk periode yang dipilih.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card card-soft border-0 h-100">
                <div class="card-body">
                    <div class="fw-semibold mb-3">Analisis Konsumsi Barang</div>
                    <div class="table-responsive">
                        <table class="table table-modern mb-0">
                            <thead>
                            <tr>
                                <th>Barang</th>
                                <th class="text-center">Keluar</th>
                                <th class="text-center">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $topConsumption = $consumptionReport->take(5);
                            @endphp
                            @forelse($topConsumption as $row)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $row['inventory']->name }}</div>
                                        <div class="small text-muted">{{ $row['inventory']->code }}</div>
                                    </td>
                                    <td class="text-center text-danger">
                                        -{{ number_format($row['total_out']) }}
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $statusClass = $row['status'] === 'Aman'
                                                ? 'badge-status-aman'
                                                : ($row['status'] === 'Warning' ? 'badge-status-warning' : 'badge-status-reorder');
                                        @endphp
                                        <span class="badge-status {{ $statusClass }}">{{ $row['status'] }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">
                                        Belum ada data konsumsi pada periode ini.
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
            <div class="fw-semibold mb-3">Evaluasi Supplier</div>
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                    <tr>
                        <th>Supplier</th>
                        <th class="text-center">Total Qty Masuk</th>
                        <th class="text-center">Jumlah Item</th>
                        <th class="text-center">Item Warning / Reorder</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($supplierReport as $row)
                        <tr>
                            <td>{{ $row['supplier'] }}</td>
                            <td class="text-center">{{ number_format($row['total_quantity']) }}</td>
                            <td class="text-center">{{ number_format($row['items_count']) }}</td>
                            <td class="text-center">
                                @if($row['critical_items'] > 0)
                                    <span class="badge-status badge-status-warning">
                                        {{ $row['critical_items'] }} item
                                    </span>
                                @else
                                    <span class="badge-status badge-status-aman">
                                        0 item
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">
                                Belum ada data pemasok pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

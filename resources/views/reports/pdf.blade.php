<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #111827;
        }

        .header-table {
            width: 100%;
            margin-bottom: 12px;
        }

        .header-logo {
            width: 70px;
            vertical-align: top;
        }

        .header-logo img {
            height: 48px;
            width: 48px;
            border-radius: 999px;
        }

        .header-text {
            text-align: left;
        }

        .brand-name {
            font-size: 12px;
            font-weight: 600;
            color: #111827;
        }

        .brand-subtitle {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 2px;
        }

        .report-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .meta {
            font-size: 10px;
            color: #6b7280;
            margin-top: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        th, td {
            border: 1px solid #e5e7eb;
            padding: 4px 6px;
        }

        th {
            background-color: #f3f4f6;
            font-weight: 600;
            font-size: 10px;
        }

        td {
            font-size: 10px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .status-aman {
            color: #15803d;
        }

        .status-warning {
            color: #b45309;
        }

        .status-reorder {
            color: #b91c1c;
        }

        .section-title {
            font-weight: 600;
            margin: 12px 0 6px;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td class="header-logo">
                <img src="{{ public_path('img/images.png') }}" alt="Logo">
            </td>
            <td class="header-text">
                <div class="brand-name">Monitoring Inventory</div>
                <div class="brand-subtitle">General Consumable</div>
                <div class="report-title">Laporan Stok</div>
                <div class="meta">
                    Periode:
                    @if($fromDate || $toDate)
                        {{ $fromDate ?: 'awal' }} s.d. {{ $toDate ?: 'sekarang' }}
                    @else
                        Semua periode
                    @endif
                    <br>
                    Dicetak pada: {{ now()->format('d/m/Y H:i') }}
                </div>
            </td>
        </tr>
    </table>

    <div class="section-title">Ringkasan Stok per Barang</div>
    <table>
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
            @php
                $statusClass = $row['status'] === 'Aman'
                    ? 'status-aman'
                    : ($row['status'] === 'Warning' ? 'status-warning' : 'status-reorder');
            @endphp
            <tr>
                <td>
                    {{ $row['inventory']->name }}
                    <br>
                    <span style="color:#6b7280; font-size:9px;">{{ $row['inventory']->code }}</span>
                </td>
                <td class="text-center">{{ number_format($row['inventory']->initial_stock) }}</td>
                <td class="text-center">+{{ number_format($row['total_in']) }}</td>
                <td class="text-center">-{{ number_format($row['total_out']) }}</td>
                <td class="text-center">{{ number_format($row['final_stock']) }}</td>
                <td class="text-center {{ $statusClass }}">{{ $row['status'] }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">
                    Belum ada data untuk periode yang dipilih.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="section-title">Evaluasi Supplier</div>
    <table>
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
                        <span class="status-warning">
                            {{ $row['critical_items'] }} item
                        </span>
                    @else
                        <span class="status-aman">
                            0 item
                        </span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">
                    Belum ada data pemasok pada periode ini.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>

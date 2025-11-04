<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kampanye Donasi Zakat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2d5a2d;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            color: #2d5a2d;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
            font-size: 12px;
        }
        .info {
            margin-bottom: 20px;
            font-size: 12px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 11px;
        }
        table thead {
            background-color: #2d5a2d;
            color: white;
        }
        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table thead th {
            padding: 10px 8px;
            font-weight: bold;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 11px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        .status-active {
            background-color: #d4edda;
            color: #155724;
            padding: 3px 6px;
            border-radius: 3px;
        }
        .status-draft {
            background-color: #e2e3e5;
            color: #383d41;
            padding: 3px 6px;
            border-radius: 3px;
        }
        .status-completed {
            background-color: #cfe2ff;
            color: #084298;
            padding: 3px 6px;
            border-radius: 3px;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Kampanye Donasi Zakat</h1>
        <p>Platform Penyaluran Zakat Transparan & Terpercaya</p>
        <p>Tanggal: {{ now()->format('d F Y') }}</p>
    </div>

    <div class="info">
        <p><strong>Total Kampanye:</strong> {{ $campaigns->count() }}</p>
        <p><strong>Total Target Dana:</strong> Rp {{ number_format($campaigns->sum('target_amount'), 0, ',', '.') }}</p>
        <p><strong>Total Dana Terkumpul:</strong> Rp {{ number_format($campaigns->sum('collected_amount'), 0, ',', '.') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Judul Kampanye</th>
                <th>Kategori</th>
                <th>Target Dana</th>
                <th>Dana Terkumpul</th>
                <th>Persentase</th>
                <th>Status</th>
                <th>Periode</th>
            </tr>
        </thead>
        <tbody>
            @foreach($campaigns as $key => $campaign)
                @php
                    $progress = $campaign->target_amount > 0
                        ? ($campaign->collected_amount / $campaign->target_amount) * 100
                        : 0;
                @endphp
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $campaign->title }}</td>
                    <td>{{ $campaign->category?->name ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</td>
                    <td class="text-right">{{ round($progress, 2) }}%</td>
                    <td>
                        @if($campaign->status === 'active')
                            <span class="status-active">Aktif</span>
                        @elseif($campaign->status === 'draft')
                            <span class="status-draft">Draft</span>
                        @else
                            <span class="status-completed">Selesai</span>
                        @endif
                    </td>
                    <td>{{ $campaign->start_date->format('d/m/Y') }} - {{ $campaign->end_date->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>&copy; {{ date('Y') }} Platform Donasi Zakat. Semua hak cipta dilindungi.</p>
        <p>Laporan ini dicetak secara otomatis oleh sistem.</p>
    </div>
</body>
</html>

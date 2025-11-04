<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Donasi Zakat</title>
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
            font-size: 10px;
        }
        table thead {
            background-color: #2d5a2d;
            color: white;
        }
        table th,
        table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        table thead th {
            padding: 8px 6px;
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
        .status-verified {
            background-color: #d4edda;
            color: #155724;
            padding: 2px 4px;
            border-radius: 2px;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            padding: 2px 4px;
            border-radius: 2px;
        }
        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
            padding: 2px 4px;
            border-radius: 2px;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Data Donasi</h1>
        <p>Platform Penyaluran Zakat Transparan & Terpercaya</p>
        <p>Tanggal: {{ now()->format('d F Y') }}</p>
    </div>

    <div class="info">
        <p><strong>Total Donasi:</strong> {{ $donations->count() }}</p>
        <p><strong>Total Nominal Donasi:</strong> Rp {{ number_format($donations->sum('amount'), 0, ',', '.') }}</p>
        @if(isset($campaign))
            <p><strong>Kampanye:</strong> {{ $campaign->title }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Donatur</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Tanggal Donasi</th>
                @if(isset($campaign))
                    <th>Catatan</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($donations as $key => $donation)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $donation->donor_name }}</td>
                    <td>{{ $donation->donor_email }}</td>
                    <td>{{ $donation->donor_phone }}</td>
                    <td class="text-right">Rp {{ number_format($donation->amount, 0, ',', '.') }}</td>
                    <td>
                        @if($donation->status === 'verified')
                            <span class="status-verified">Terverifikasi</span>
                        @elseif($donation->status === 'pending')
                            <span class="status-pending">Menunggu</span>
                        @else
                            <span class="status-rejected">Ditolak</span>
                        @endif
                    </td>
                    <td>{{ $donation->created_at->format('d/m/Y H:i') }}</td>
                    @if(isset($campaign))
                        <td>{{ $donation->note ?? '-' }}</td>
                    @endif
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

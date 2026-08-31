<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Audit Trail</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #222; }
        h2 { margin-bottom: 2px; }
        p.sub { margin-top: 0; color: #666; margin-bottom: 14px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Audit Trail</h2>
    <p class="sub">
        Periode:
        {{ $filters['dateFrom'] ?? '(awal)' }} s/d {{ $filters['dateTo'] ?? '(akhir)' }}
        &mdash; Dicetak: {{ now()->format('d/m/Y H:i') }}
    </p>

    <table>
        <thead>
            <tr>
                <th>Waktu</th>
                <th>User</th>
                <th>Aksi</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $log)
                <tr>
                    <td>{{ \Illuminate\Support\Carbon::parse($log['time'])->format('d/m/Y H:i:s') }}</td>
                    <td>{{ $log['user'] }}</td>
                    <td>{{ $log['action_label'] }}</td>
                    <td>{{ $log['detail'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center;">Tidak ada data pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>

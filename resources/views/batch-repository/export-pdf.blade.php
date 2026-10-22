<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Batch Repository</title>
    <style>
        body { font-family: "DejaVu Sans", sans-serif; font-size: 9px; color: #111; }
        h2 { margin: 0 0 2px; font-size: 14px; }
        .sub { font-size: 8.5px; color: #444; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 4px 6px; font-size: 8.5px; text-align: left; }
        th { background: #eee; }
    </style>
</head>
<body>

    <h2>Batch Repository</h2>
    <p class="sub">
        Dicetak {{ $generatedAt->format('d M Y, H:i') }}
        @if (array_filter($filters))
            &middot; Filter:
            @foreach (array_filter($filters) as $key => $value)
                {{ $key }}="{{ $value }}"@if (!$loop->last), @endif
            @endforeach
        @endif
    </p>

    <table>
        <thead>
            <tr>
                <th>Batch No</th>
                <th>Product Code</th>
                <th>Product Name</th>
                <th>Manufacturer</th>
                <th>Admin</th>
                <th>Supervisor</th>
                <th>Manager</th>
                <th>Status</th>
                <th>Batch Date</th>
                <th>Released Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($batches as $batch)
                <tr>
                    <td>{{ $batch->batch_number }}</td>
                    <td>{{ $batch->product->product_code ?? '-' }}</td>
                    <td>{{ $batch->product->name ?? '-' }}</td>
                    <td>{{ $batch->manufacturer }}</td>
                    <td>{{ $batch->creator_display_name }}</td>
                    <td>{{ $batch->supervisor_display_name }}</td>
                    <td>{{ $batch->manager_display_name }}</td>
                    <td>{{ $batch->statusLabel() }}</td>
                    <td>{{ $batch->batch_date?->format('d M Y') }}</td>
                    <td>{{ $batch->released_at?->format('d M Y') ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align:center;">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>

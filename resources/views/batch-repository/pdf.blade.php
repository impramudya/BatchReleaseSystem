<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $batch->batch_number }}</title>
    <style>
        @page { margin: 90px 35px 60px 35px; }

        body { font-family: "DejaVu Sans", sans-serif; font-size: 9px; color: #111; }

        header { position: fixed; top: -70px; left: 0; right: 0; height: 70px; }
        footer { position: fixed; bottom: -45px; left: 0; right: 0; height: 30px; font-size: 8px; color: #444; }

        .header-table { width: 100%; border-bottom: 2px solid #000; padding-bottom: 6px; }
        .header-table td { vertical-align: top; }
        .company-name { font-weight: bold; font-size: 12px; }
        .company-sub { font-size: 8px; }
        .form-title { text-align: center; font-size: 13px; font-weight: bold; padding-top: 6px; }

        .meta-table { width: 100%; margin: 8px 0 10px; }
        .meta-table td { padding: 1.5px 4px; font-size: 9.5px; }
        .meta-label { width: 90px; }

        table.checklist { width: 100%; border-collapse: collapse; }
        table.checklist th, table.checklist td {
            border: 1px solid #000;
            padding: 3px 5px;
            font-size: 8.5px;
            vertical-align: top;
        }
        table.checklist th { background: #eee; text-align: center; }
        .col-no { width: 3.5%; text-align: center; }
        .col-answer { width: 5.5%; text-align: center; }
        .col-ket { width: 18%; }
        tr.category-row td { background: #dcdcdc; font-weight: bold; }

        .box {
            display: inline-block;
            width: 9px;
            height: 9px;
            border: 1px solid #000;
            text-align: center;
            line-height: 9px;
            font-size: 8px;
        }
        .box-checked { background: #000; color: #fff; }

        .conclusion { margin: 10px 0; font-size: 9.5px; }
        .conclusion u { font-weight: bold; }

        table.sign-table { width: 100%; margin-top: 14px; }
        table.sign-table td.sign-box {
            width: 48%;
            border: 1px solid #000;
            padding: 6px 8px;
            height: 70px;
            vertical-align: top;
            font-size: 9px;
        }
        table.sign-table td.sign-gap { width: 4%; }
        .sign-name { margin-top: 26px; font-weight: bold; }
        .sign-role { font-size: 8px; color: #333; }
        .sign-time { font-size: 8px; color: #333; }

        .footer-table { width: 100%; }
        .footer-table td.right { text-align: right; }
    </style>
</head>
<body>

    <header>
        <table class="header-table">
            <tr>
                <td width="55%">
                    <span class="company-name">PT. SAKA FARMA</span><br>
                    <span class="company-sub">LABORATORIES</span><br>
                    <span class="company-sub">A Kalbe Company</span>
                </td>
                <td width="45%">
                    <div class="form-title">{{ $formTitle }}</div>
                </td>
            </tr>
        </table>
    </header>

    <footer>
        <table class="footer-table">
            <tr>
                <td>{{ $sopCode }}</td>
                <td class="right">Dicetak {{ now()->format('d M Y, H:i') }}</td>
            </tr>
        </table>
    </footer>

    <table class="meta-table">
        <tr>
            <td class="meta-label">Kode Produk</td>
            <td>: {{ $batch->product->product_code ?? '-' }}</td>
        </tr>
        <tr>
            <td class="meta-label">No. Batch</td>
            <td>: {{ $batch->batch_number }}</td>
        </tr>
        <tr>
            <td class="meta-label">Manufacturer</td>
            <td>: {{ $batch->manufacturer }}</td>
        </tr>
        <tr>
            <td class="meta-label">Tanggal</td>
            <td>: {{ $batch->batch_date?->format('d M Y') }}</td>
        </tr>
    </table>

    <table class="checklist">
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th>Daftar Isi</th>
                <th class="col-answer">*C</th>
                <th class="col-answer">NC</th>
                <th class="col-answer">N/A</th>
                <th class="col-ket">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 0; @endphp
            @forelse ($checklistRows as $categoryCode => $rows)
                <tr class="category-row">
                    <td>{{ $categoryCode }}</td>
                    <td colspan="5">{{ $rows->first()->category_name }}</td>
                </tr>
                @foreach ($rows as $row)
                    @php $no++; @endphp
                    <tr>
                        <td class="col-no">{{ $no }}</td>
                        <td>{{ $row->question }}</td>
                        <td class="col-answer"><span class="box {{ $row->answer === 'C' ? 'box-checked' : '' }}">{{ $row->answer === 'C' ? 'X' : '' }}</span></td>
                        <td class="col-answer"><span class="box {{ $row->answer === 'NC' ? 'box-checked' : '' }}">{{ $row->answer === 'NC' ? 'X' : '' }}</span></td>
                        <td class="col-answer"><span class="box {{ $row->answer === 'NA' ? 'box-checked' : '' }}">{{ $row->answer === 'NA' ? 'X' : '' }}</span></td>
                        <td class="col-ket"></td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">Belum ada jawaban checklist untuk batch ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p class="conclusion">
        <strong>Kesimpulan:</strong> Produk diluluskan / ditolak untuk didistribusikan &mdash;
        @if ($batch->status === 'released')
            <u>DILULUSKAN</u>
        @elseif ($batch->status === 'rejected')
            <u>DITOLAK</u>
        @else
            (belum diputuskan &mdash; status saat ini: {{ $batch->statusLabel() }})
        @endif
    </p>

    @php
        $supApproval = $batch->approvals->firstWhere('step', 'supervisor');
        $mgrApproval = $batch->approvals->firstWhere('step', 'manager');
    @endphp

    <table class="sign-table">
        <tr>
            <td class="sign-box">
                Diperiksa Oleh
                <div class="sign-name">{{ $batch->supervisor->name ?? '-' }}</div>
                <div class="sign-role">QA Admin / QA Supervisor</div>
                <div class="sign-time">
                    @if ($supApproval?->status === 'approved')
                        Approved &middot; {{ $supApproval->acted_at?->format('d M Y, H:i') }}
                    @elseif ($supApproval?->status === 'rejected')
                        Rejected &middot; {{ $supApproval->acted_at?->format('d M Y, H:i') }}
                    @else
                        Menunggu approval
                    @endif
                </div>
            </td>
            <td class="sign-gap"></td>
            <td class="sign-box">
                Disetujui Oleh
                <div class="sign-name">{{ $batch->manager->name ?? '-' }}</div>
                <div class="sign-role">QA Manager</div>
                <div class="sign-time">
                    @if ($mgrApproval?->status === 'approved')
                        Approved &middot; {{ $mgrApproval->acted_at?->format('d M Y, H:i') }}
                    @elseif ($mgrApproval?->status === 'rejected')
                        Rejected &middot; {{ $mgrApproval->acted_at?->format('d M Y, H:i') }}
                    @else
                        Menunggu approval
                    @endif
                </div>
            </td>
        </tr>
    </table>

</body>
</html>

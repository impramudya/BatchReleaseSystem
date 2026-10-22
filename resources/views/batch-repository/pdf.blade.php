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

        .header-table { width: 100%; border-bottom: 2px solid #000; padding-bottom: 5px; }
        .header-table td { vertical-align: middle; }
        .company-name { font-weight: bold; font-size: 12px; }
        .company-sub { font-size: 8px; }
        .form-title { text-align: center; font-size: 13px; font-weight: bold; }

        .meta-table { width: 100%; margin: 8px 0 10px; }
        .meta-table td { padding: 1.5px 4px; font-size: 9.5px; }
        .meta-label { width: 90px; }

        table.checklist {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            border: 1px solid #000;
        }
        table.checklist > thead > tr > th {
            border: 1px solid #000;
            background: #fff;
            text-align: center;
            padding: 3px 5px;
            font-size: 8.5px;
        }
        table.checklist > tbody > tr > td {
            border-left: 1px solid #000;
            border-right: 1px solid #000;
            border-top: none;
            border-bottom: none;
            padding: 2.5px 5px;
            font-size: 8.5px;
            vertical-align: top;
            word-wrap: break-word;
            word-break: break-word;
        }
        .col-no { width: 4%; text-align: center; }
        .col-daftar { width: 56%; }
        .col-answer { width: 6%; text-align: center; }
        .col-ket { width: 22%; word-wrap: break-word; word-break: break-word; vertical-align: top; }
        .col-ket-has-line { vertical-align: top !important; }
        .ket-line-inner {
            height: 10px;
            border-bottom: 1px solid #000;
            margin: 0 4px;
        }
        tr.category-row > td {
            border-top: 1px solid #000 !important;
            border-bottom: 1px solid #000 !important;
            background: #fff !important;
            font-weight: bold;
            padding: 3px 5px;
        }
        table.checklist > tbody > tr:last-child > td {
            border-bottom: 1px solid #000;
        }

        .box {
            display: inline-block;
            width: 9px;
            height: 9px;
            border: 1px solid #000;
            text-align: center;
            line-height: 9px;
            font-size: 8px;
            vertical-align: middle;
        }
        .box-checked { background: #000; color: #fff; }

        tr.point-section-row { page-break-inside: avoid; }
        tr.point-section-row > td {
            border-top: 1px solid #000 !important;
            border-bottom: 1px solid #000 !important;
            border-left: 1px solid #000 !important;
            border-right: 1px solid #000 !important;
            vertical-align: top;
        }
        td.point-letter {
            text-align: center;
            font-weight: bold;
            font-size: 9px;
            vertical-align: top;
            padding-top: 5px;
        }
        td.point-cell {
            padding: 5px 8px !important;
            vertical-align: top;
        }

        table.point-layout,
        table.point-layout tr,
        table.point-layout td {
            border: none !important;
            border-top: none !important;
            border-bottom: none !important;
            border-left: none !important;
            border-right: none !important;
            background: transparent !important;
        }
        table.point-layout {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            padding: 0;
        }
        table.point-layout td {
            padding: 1.5px 0 !important;
            font-size: 8.5px;
            vertical-align: top;
        }

        .nomor-laporan-box {
            border: 1px solid #000;
            padding: 2px 4px;
            min-height: 14px;
            font-size: 8px;
            width: 92%;
            box-sizing: border-box;
            background: #fff;
        }

        table.inner-sub-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000 !important;
            margin-top: 2px;
        }
        table.inner-sub-table th, table.inner-sub-table td {
            border: 1px solid #000 !important;
            padding: 2.5px 4px !important;
            font-size: 8px;
            text-align: center;
        }
        table.inner-sub-table th {
            background: #fff !important;
            font-weight: bold;
        }

        table.conclusion-table {
            width: 100%;
            border-collapse: collapse;
            border: 1.5px solid #000;
            margin: 8px 0 6px 0;
        }
        table.conclusion-table td {
            border: none;
            padding: 5px 8px;
            font-size: 9px;
            vertical-align: middle;
        }
        table.conclusion-table strong { font-weight: bold; }
        .strikethrough { text-decoration: line-through; }

        table.sign-table { width: 100%; margin-top: 12px; }
        table.sign-table td.sign-box {
            width: 48%;
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
            font-size: 9px;
        }
        table.sign-table td.sign-gap { width: 4%; }
        .sign-title { font-size: 9px; }
        .sign-name { margin-top: 4px; font-weight: bold; font-size: 9px; }
        .sign-role { font-size: 8px; color: #333; margin-top: 1px; }
        .sign-time { font-size: 8px; color: #333; margin-top: 2px; }

        .footer-table { width: 100%; }
        .footer-table td.right { text-align: right; }
    </style>
</head>
<body>

    <header>
        <table class="header-table">
            <tr>
                <td style="width: 20%; vertical-align: middle;">
                    <span class="company-name">PT. SAKA FARMA</span><br>
                    <span class="company-sub">LABORATORIES</span><br>
                    <span class="company-sub">A Kalbe Company</span>
                </td>
                <td style="width: 60%; text-align: center; vertical-align: middle;">
                    <div class="form-title">{{ $formTitle }}</div>
                </td>
                <td style="width: 20%; vertical-align: middle;"></td>
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
                <th class="col-daftar">Daftar Isi</th>
                <th class="col-answer">C</th>
                <th class="col-answer">NC</th>
                <th class="col-answer">N/A</th>
                <th class="col-ket">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 0; @endphp
            @forelse ($checklistRows as $categoryCode => $rows)
                @php
                    $emptyBlocks = [];
                    $currentBlock = [];
                    foreach ($rows as $idx => $r) {
                        $hasText = !empty(trim($r->keterangan ?? ''));
                        if (!$hasText) {
                            $currentBlock[] = $idx;
                        } else {
                            if (!empty($currentBlock)) {
                                $emptyBlocks[] = $currentBlock;
                                $currentBlock = [];
                            }
                        }
                    }
                    if (!empty($currentBlock)) {
                        $emptyBlocks[] = $currentBlock;
                    }

                    $blockStarts = [];
                    $blockEnds = [];
                    foreach ($emptyBlocks as $bIdx => $bRows) {
                        $bTag = preg_replace('/[^a-zA-Z0-9_]/', '_', $categoryCode) . '_' . $bIdx;
                        $blockStarts[$bRows[0]] = $bTag;
                        $blockEnds[end($bRows)] = $bTag;
                    }
                @endphp
                <tr class="category-row">
                    <td class="col-no point-letter">{{ $categoryCode }}</td>
                    <td colspan="5">{{ $rows->first()->category_name }}</td>
                </tr>
                @foreach ($rows as $idx => $row)
                    @php
                        $depth = $row->depth ?? 0;
                        $hasCheckmark = !empty($row->is_checkable);
                        $indent = $depth === 2 ? 'padding-left: 20px;' : ($depth === 1 ? 'padding-left: 10px;' : '');
                        $hasTextKet = !empty(trim($row->keterangan ?? ''));
                        $bStartTag = isset($blockStarts[$idx]) ? 'diag-start-' . $blockStarts[$idx] : '';
                        $bEndTag = isset($blockEnds[$idx]) ? 'diag-end-' . $blockEnds[$idx] : '';
                    @endphp
                    <tr>
                        <td class="col-no">{{ $row->parent_no ?? '' }}</td>
                        <td class="col-daftar" style="{{ $indent }} {{ $depth === 0 ? 'font-weight: bold;' : '' }}">
                            <div>{{ $row->question }}</div>
                            @if (!empty($row->details))
                                <div style="font-size: 8px; color: #333; margin-top: 3px; padding-left: 10px; line-height: 1.35; font-weight: normal;">
                                    {!! nl2br(e($row->details)) !!}
                                </div>
                            @endif
                        </td>
                        <td class="col-answer">
                            @if ($hasCheckmark)
                                <span class="box {{ $row->answer === 'C' ? 'box-checked' : '' }}">{{ $row->answer === 'C' ? 'X' : '' }}</span>
                            @endif
                        </td>
                        <td class="col-answer">
                            @if ($hasCheckmark)
                                <span class="box {{ $row->answer === 'NC' ? 'box-checked' : '' }}">{{ $row->answer === 'NC' ? 'X' : '' }}</span>
                            @endif
                        </td>
                        <td class="col-answer">
                            @if ($hasCheckmark)
                                <span class="box {{ $row->answer === 'NA' ? 'box-checked' : '' }}">{{ $row->answer === 'NA' ? 'X' : '' }}</span>
                            @endif
                        </td>
                        <td class="col-ket {{ $hasCheckmark ? 'col-ket-has-line' : '' }} {{ $bStartTag }} {{ $bEndTag }}">
                            @if ($hasCheckmark)
                                @if ($hasTextKet)
                                    <div style="padding-left: 4px; padding-right: 4px; font-size: 8px; line-height: 1.1;">
                                        {!! nl2br(e($row->keterangan)) !!}
                                    </div>
                                    <div style="border-bottom: 1px solid #000; margin: 2px 4px 0 4px;"></div>
                                @else
                                    <div class="ket-line-inner"></div>
                                @endif
                            @endif
                            @if (isset($blockEnds[$idx]))
                                @php $activeEndTag = $blockEnds[$idx]; @endphp
                                <script type="text/php">
                                    $sBox = null;
                                    $eBox = null;
                                    $find = function($f) use (&$find, &$sBox, &$eBox) {
                                        $node = $f->get_node();
                                        if ($node && $node->nodeType === XML_ELEMENT_NODE) {
                                            $cls = $node->getAttribute("class");
                                            if (strpos($cls, "diag-start-{{ $activeEndTag }}") !== false) $sBox = $f->get_border_box();
                                            if (strpos($cls, "diag-end-{{ $activeEndTag }}") !== false) $eBox = $f->get_border_box();
                                        }
                                        if ($sBox && $eBox) return;
                                        foreach ($f->get_children() as $c) {
                                            $find($c);
                                            if ($sBox && $eBox) return;
                                        }
                                    };
                                    $find($pdf->get_dompdf()->getTree()->get_root());
                                    if ($sBox && $eBox) {
                                        $x1 = $sBox["x"];
                                        $y1 = $sBox["y"];
                                        $x2 = $eBox["x"] + $eBox["w"];
                                        $y2 = $eBox["y"] + $eBox["h"];
                                        $pdf->line($x1, $y1, $x2, $y2, [0, 0, 0], 0.8);
                                    }
                                </script>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">Belum ada jawaban checklist untuk batch ini.</td>
                </tr>
            @endforelse
            @php
                $lastCatKey = $checklistRows->keys()->filter()->last() ?? $batch->productionLine?->categories?->sortBy('code')->last()?->code;
                $baseCharCode = ($lastCatKey && ctype_alpha(trim($lastCatKey)[0]))
                    ? ord(strtoupper(trim($lastCatKey)[0]))
                    : ord('A') - 1;

                $validasiPointCode = chr($baseCharCode + 1);
                $penyimpanganPointCode = chr($baseCharCode + 2);
                $changeControlPointCode = chr($baseCharCode + 3);

                $lapVal = ($batch->batch_validasi === 'tidak') ? 'tidak' : $batch->laporan_validasi;
                $noLapVal = ($batch->batch_validasi === 'tidak') ? 'N/A' : ($batch->nomor_laporan_validasi ?? '');
                $penyimpanganVal = ($batch->laporan_penyimpangan === 'ada') ? 'ada' : 'tidak';
                $ccVal = ($batch->change_control === 'ada') ? 'ada' : 'tidak';
            @endphp

            {{-- Point: Batch Validasi --}}
            <tr class="point-section-row">
                <td class="col-no point-letter">{{ $validasiPointCode }}</td>
                <td colspan="5" class="point-cell">
                    <table class="point-layout">
                        <tr>
                            <td style="width: 52%; font-weight: bold;">Batch Validasi*</td>
                            <td style="width: 48%;">
                                <span class="box {{ $batch->batch_validasi === 'ya' ? 'box-checked' : '' }}">{{ $batch->batch_validasi === 'ya' ? 'X' : '' }}</span> &nbsp;Ya
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="box {{ $batch->batch_validasi === 'tidak' ? 'box-checked' : '' }}">{{ $batch->batch_validasi === 'tidak' ? 'X' : '' }}</span> &nbsp;Tidak
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 52%; padding-top: 2px !important;">Jika Ya :</td>
                            <td style="width: 48%;"></td>
                        </tr>
                        <tr>
                            <td style="width: 52%;">Laporan Validasi*</td>
                            <td style="width: 48%;">
                                <span class="box {{ $lapVal === 'ada' ? 'box-checked' : '' }}">{{ $lapVal === 'ada' ? 'X' : '' }}</span> &nbsp;Ada
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="box {{ $lapVal === 'tidak' ? 'box-checked' : '' }}">{{ $lapVal === 'tidak' ? 'X' : '' }}</span> &nbsp;Tidak
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 52%; padding-top: 3px !important; vertical-align: middle !important;">Nomor Laporan</td>
                            <td style="width: 48%; padding-top: 3px !important; padding-right: 8px !important;">
                                <div class="nomor-laporan-box">
                                    {{ !empty($noLapVal) ? $noLapVal : 'N/A' }}
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            {{-- Point: Laporan Penyimpangan --}}
            <tr class="point-section-row">
                <td class="col-no point-letter">{{ $penyimpanganPointCode }}</td>
                <td colspan="5" class="point-cell">
                    <table class="point-layout">
                        <tr>
                            <td style="width: 52%; font-weight: bold;">Laporan Penyimpangan*</td>
                            <td style="width: 48%;">
                                <span class="box {{ $penyimpanganVal === 'ada' ? 'box-checked' : '' }}">{{ $penyimpanganVal === 'ada' ? 'X' : '' }}</span> &nbsp;Ada
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="box {{ $penyimpanganVal === 'tidak' ? 'box-checked' : '' }}">{{ $penyimpanganVal === 'tidak' ? 'X' : '' }}</span> &nbsp;Tidak
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 52%; padding-top: 2px !important;">Jika Ada :</td>
                            <td style="width: 48%;"></td>
                        </tr>
                        <tr>
                            <td style="width: 52%;">
                                <table class="inner-sub-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 50%;">No. Penyimpangan</th>
                                            <th style="width: 50%;">Status Penyimpangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $penyimpanganList = $batch->penyimpangan_items ?? [];
                                        @endphp
                                        @forelse ($penyimpanganList as $item)
                                            <tr>
                                                <td>{{ !empty($item['nomor']) ? $item['nomor'] : 'N/A' }}</td>
                                                <td>{{ !empty($item['status']) ? $item['status'] : 'N/A' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td style="height: 28px;">N/A</td>
                                                <td style="height: 28px;">N/A</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </td>
                            <td style="width: 48%;"></td>
                        </tr>
                    </table>
                </td>
            </tr>

            {{-- Point: Change Control --}}
            <tr class="point-section-row">
                <td class="col-no point-letter">{{ $changeControlPointCode }}</td>
                <td colspan="5" class="point-cell">
                    <table class="point-layout">
                        <tr>
                            <td style="width: 52%; font-weight: bold;">Change Control*</td>
                            <td style="width: 48%;">
                                <span class="box {{ $ccVal === 'ada' ? 'box-checked' : '' }}">{{ $ccVal === 'ada' ? 'X' : '' }}</span> &nbsp;Ada
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="box {{ $ccVal === 'tidak' ? 'box-checked' : '' }}">{{ $ccVal === 'tidak' ? 'X' : '' }}</span> &nbsp;Tidak
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 52%; padding-top: 2px !important;">Jika Ada :</td>
                            <td style="width: 48%;"></td>
                        </tr>
                        <tr>
                            <td style="width: 52%;">
                                <table class="inner-sub-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 50%;">No. FUP</th>
                                            <th style="width: 50%;">Status FUP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $ccList = $batch->change_control_items ?? [];
                                        @endphp
                                        @forelse ($ccList as $item)
                                            <tr>
                                                <td>{{ !empty($item['nomor']) ? $item['nomor'] : 'N/A' }}</td>
                                                <td>{{ !empty($item['status']) ? $item['status'] : 'N/A' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td style="height: 28px;">N/A</td>
                                                <td style="height: 28px;">N/A</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </td>
                            <td style="width: 48%;"></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="conclusion-table">
        <tr>
            <td>
                <strong>Kesimpulan</strong> &nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;
                @if ($batch->status === 'released')
                    Produk diluluskan / <span class="strikethrough">ditolak untuk didistribusikan</span>
                @elseif ($batch->status === 'rejected')
                    <span class="strikethrough">Produk diluluskan</span> / ditolak untuk didistribusikan
                @else
                    Produk diluluskan / ditolak untuk didistribusikan
                @endif
            </td>
        </tr>
    </table>

    @php
        $adminApproval = $batch->approvals->firstWhere('step', 'admin');
        $supApproval = $batch->approvals->firstWhere('step', 'supervisor');
        $mgrApproval = $batch->approvals->firstWhere('step', 'manager');

        $approvalNotes = [];
        if (!empty(trim($supApproval?->note ?? ''))) {
            $approvalNotes[] = 'Supervisor: ' . trim($supApproval->note);
        }
        if (!empty(trim($mgrApproval?->note ?? ''))) {
            $approvalNotes[] = 'Manager: ' . trim($mgrApproval->note);
        }
        if (empty($approvalNotes) && !empty(trim($batch->keterangan ?? ''))) {
            $approvalNotes[] = trim($batch->keterangan);
        }

        $adminName = $batch->creator->name 
            ?? $adminApproval?->user?->name 
            ?? 'Admin';
        $supervisorName = $batch->supervisor->name 
            ?? $supApproval?->user?->name 
            ?? 'Supervisor';
        $managerName = $batch->manager->name 
            ?? $mgrApproval?->user?->name 
            ?? 'Manager';

        $isSupApproved = ($supApproval?->status === 'approved');
        $isMgrApproved = ($mgrApproval?->status === 'approved');

        $displayNameDiperiksa = $isSupApproved 
            ? "{$adminName} / {$supervisorName}" 
            : $adminName;

        $displayNameDisetujui = $isMgrApproved 
            ? $managerName 
            : '';
    @endphp

    <table class="notes-table" style="width: 100%; border-collapse: collapse; margin: 4px 0 6px 0; font-size: 8.5px;">
        <tr>
            <td style="width: 15%; vertical-align: top; font-weight: bold; white-space: nowrap;">Catatan Tambahan</td>
            <td style="width: 2%; vertical-align: top; font-weight: bold; text-align: center;">:</td>
            <td style="width: 83%; vertical-align: top;">
                @if (!empty($approvalNotes))
                    {!! nl2br(e(implode("\n", $approvalNotes))) !!}
                @else
                    N/A
                @endif
            </td>
        </tr>
    </table>

    <div style="font-size: 8px; margin-top: 4px; margin-bottom: 6px; line-height: 1.4;">
        <div>C = Complies &nbsp;&nbsp;&nbsp;&nbsp; NC = Not Complies &nbsp;&nbsp;&nbsp;&nbsp; N/A = Not Applicable</div>
    </div>

    <table class="sign-table">
        <tr>
            <td class="sign-box">
                <div class="sign-title">Diperiksa Oleh</div>
                <div class="sign-name">{{ $displayNameDiperiksa }}</div>
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
                <div class="sign-title">Disetujui Oleh</div>
                <div class="sign-name">{!! $displayNameDisetujui !== '' ? e($displayNameDisetujui) : '&nbsp;' !!}</div>
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

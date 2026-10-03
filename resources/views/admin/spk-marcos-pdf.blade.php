<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Resmi SPK MARCOS - Glow & Grace</title>
    
    <!-- Google Fonts for Editorial Formal Document -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* A4 Portrait Print Stylesheet */
        @page {
            size: A4 portrait;
            margin: 14mm 16mm 16mm 16mm;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.45;
            color: #1a1a1a;
            background: #fdfdfd;
        }

        /* Screen Control Bar */
        .no-print-bar {
            background: #1A2B28;
            color: #FFFFFF;
            padding: 14px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 14px rgba(0,0,0,0.15);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .btn-print {
            background: #C5A059;
            color: #1A2B28;
            border: none;
            padding: 8px 20px;
            font-size: 13px;
            font-weight: 700;
            border-radius: 6px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-back {
            background: rgba(255,255,255,0.15);
            color: #FFFFFF;
            text-decoration: none;
            padding: 8px 16px;
            font-size: 13px;
            border-radius: 6px;
        }

        /* Printable Document Sheet */
        .document-sheet {
            max-width: 210mm;
            margin: 20px auto;
            background: #FFFFFF;
            padding: 20mm 20mm;
            box-shadow: 0 0 16px rgba(0,0,0,0.06);
            border: 1px solid #E5E7EB;
        }

        /* Kop Surat Glow & Grace */
        .kop-surat {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding-bottom: 12px;
            text-align: center;
        }

        .kop-logo {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #1A2B28;
            color: #C5A059;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 800;
            flex-shrink: 0;
            border: 2px solid #C5A059;
        }

        .kop-text {
            flex: 1;
        }

        .kop-brand {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 20pt;
            font-weight: 800;
            color: #1A2B28;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .kop-sub {
            font-size: 9.5pt;
            font-weight: 600;
            color: #50625E;
            margin-bottom: 3px;
        }

        .kop-address {
            font-size: 8.5pt;
            color: #4B5563;
            line-height: 1.35;
        }

        /* Garis Ganda Pembatas Kop Surat */
        .kop-divider {
            border: none;
            border-top: 3px solid #1A2B28;
            margin-top: 4px;
            margin-bottom: 2px;
        }

        .kop-divider-sub {
            border: none;
            border-top: 1px solid #C5A059;
            margin-bottom: 18px;
        }

        /* Meta Informasi Dokumen */
        .doc-title-block {
            text-align: center;
            margin-bottom: 16px;
        }

        .doc-title {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 13pt;
            font-weight: 700;
            color: #1A2B28;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            margin-bottom: 4px;
        }

        .doc-subtitle {
            font-size: 9pt;
            color: #50625E;
            font-style: italic;
        }

        .meta-box {
            display: flex;
            justify-content: space-between;
            font-size: 9pt;
            background: #FAF8F5;
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px solid #EAE5D9;
            margin-bottom: 18px;
        }

        /* Formal Tables */
        h2.section-header {
            font-family: 'Playfair Display', serif;
            font-size: 10.5pt;
            font-weight: 700;
            color: #1A2B28;
            margin-top: 16px;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            border-bottom: 1.5px solid #1A2B28;
            padding-bottom: 3px;
        }

        table.formal-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.8pt;
            margin-bottom: 14px;
        }

        table.formal-table th {
            background: #F4F1EA;
            color: #1A2B28;
            font-weight: 700;
            padding: 6px 8px;
            border: 1px solid #D1C5B4;
            text-align: left;
            text-transform: uppercase;
            font-size: 8pt;
            letter-spacing: 0.02em;
        }

        table.formal-table td {
            padding: 5px 8px;
            border: 1px solid #D1C5B4;
            color: #2D3748;
            vertical-align: middle;
        }

        table.formal-table tr:nth-child(even) td {
            background: #FDFBF8;
        }

        .rank-gold {
            background: #FFFDF0 !important;
            font-weight: 700;
        }

        .rank-badge {
            display: inline-block;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 4px;
            font-size: 8pt;
        }

        .badge-1 { background: #FFDEA5; color: #4E3700; }
        .badge-2 { background: #E2E8F0; color: #334155; }
        .badge-3 { background: #FDE8E3; color: #7C2D12; }

        /* Tanda Tangan / Lembar Pengesahan */
        .signature-section {
            margin-top: 28px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        .sig-box {
            width: 220px;
            text-align: center;
            font-size: 9pt;
        }

        .sig-space {
            height: 60px;
        }

        .sig-name {
            font-weight: 700;
            text-decoration: underline;
            color: #1A2B28;
        }

        /* Print Media Override */
        @media print {
            body {
                background: #FFFFFF !important;
            }
            .no-print-bar {
                display: none !important;
            }
            .document-sheet {
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border: none !important;
                max-width: 100% !important;
                width: 100% !important;
            }
            tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    <!-- Bar Kontrol Layar (Tidak Tercetak) -->
    <div class="no-print-bar">
        <div style="font-weight: 600; font-size: 14px;">
            Glow & Grace &mdash; Pratinjau Dokumen Cetak SPK MARCOS (Format A4 Portrait)
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="{{ route('admin.spk') }}" class="btn-back">&larr; Kembali ke Admin</a>
            <button type="button" class="btn-print" onclick="window.print()">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Lembar Dokumen Resmi A4 -->
    <div class="document-sheet">
        <!-- 1. KOP SURAT RESMI GLOW & GRACE -->
        <div class="kop-surat">
            <div class="kop-logo">
                G&G
            </div>
            <div class="kop-text">
                <div class="kop-brand">GLOW &amp; GRACE WEDDING MARKETPLACE</div>
                <div class="kop-sub">Sistem Pendukung Keputusan Pemilihan Jasa Make-Up &amp; Dekorasi Pernikahan</div>
                <div class="kop-address">
                    Kantor Pengelola: Gedung Menara Kencana Lt. 4, Jl. Ringroad No. 88, Medan, Sumatera Utara<br>
                    Website: <strong>www.glowgrace.com</strong> &bull; Email Resmi: <strong>official@glowgrace.com</strong>
                </div>
            </div>
            <div style="width: 70px;"></div>
        </div>

        <hr class="kop-divider">
        <hr class="kop-divider-sub">

        <!-- 2. JUDUL DOKUMEN & METADATA -->
        <div class="doc-title-block">
            <h1 class="doc-title">Laporan Hasil Keputusan &amp; Perankingan Alternatif</h1>
            <div class="doc-subtitle">Evaluasi Komputasi Objektif Menggunakan Metode MARCOS Baku</div>
        </div>

        <div class="meta-box">
            <div>
                <strong>No. Dokumen:</strong> SPK-MARCOS/GG/{{ date('Ymd') }}/{{ str_pad(auth()->id() ?? 1, 3, '0', STR_PAD_LEFT) }}<br>
                <strong>Perihal:</strong> Rekomendasi Solusi Kompromi Paket Pernikahan
            </div>
            <div style="text-align: right;">
                <strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y, HH:mm') }} WIB<br>
                <strong>Penanggung Jawab:</strong> {{ auth()->user()->name ?? 'Administrator SPK' }}
            </div>
        </div>

        <!-- 3. TABEL 1: HASIL PERANKINGAN AKHIR -->
        <h2 class="section-header">1. Tabel Peringkat Rekomendasi Solusi Kompromi (Hasil Akhir)</h2>
        <table class="formal-table">
            <thead>
                <tr>
                    <th style="width: 38px; text-align: center;">Rank</th>
                    <th>Nama Paket Bundling</th>
                    <th>Mitra Vendor</th>
                    <th style="text-align: right; width: 75px;">$K_i^+$ (Ideal)</th>
                    <th style="text-align: right; width: 75px;">$K_i^-$ (Anti)</th>
                    <th style="text-align: right; width: 85px;">Skor $f(K_i)$</th>
                    <th style="text-align: center; width: 100px;">Status Keputusan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($marcosData['rankings'] as $row)
                    @php
                        $isTop = $row['rank'] <= 3;
                        $rankClass = $row['rank'] === 1 ? 'rank-gold' : '';
                    @endphp
                    <tr class="{{ $rankClass }}">
                        <td style="text-align: center;">
                            @if($row['rank'] === 1)
                                <span class="rank-badge badge-1">#1</span>
                            @elseif($row['rank'] === 2)
                                <span class="rank-badge badge-2">#2</span>
                            @elseif($row['rank'] === 3)
                                <span class="rank-badge badge-3">#3</span>
                            @else
                                #{{ $row['rank'] }}
                            @endif
                        </td>
                        <td><strong>{{ $row['package_name'] }}</strong></td>
                        <td>{{ $row['vendor_name'] }}</td>
                        <td style="text-align: right; font-family: monospace;">{{ number_format($row['K_plus'] ?? $row['k_plus'] ?? 0, 4) }}</td>
                        <td style="text-align: right; font-family: monospace;">{{ number_format($row['K_minus'] ?? $row['k_minus'] ?? 0, 4) }}</td>
                        <td style="text-align: right; font-family: monospace; font-weight: 700; color: #1A2B28;">
                            {{ number_format($row['final_score'], 4) }}
                        </td>
                        <td style="text-align: center;">
                            @if($row['rank'] === 1)
                                <strong style="color: #4E3700;">Rekomendasi Utama</strong>
                            @elseif($row['rank'] <= 3)
                                <span>Pilihan Prioritas</span>
                            @else
                                <span style="color: #6B7280;">Alternatif Teruji</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- 4. TABEL 2: KRITERIA DAN BOBOT -->
        <h2 class="section-header">2. Parameter Kriteria &amp; Bobot Penilaian ($w_j$)</h2>
        <table class="formal-table">
            <thead>
                <tr>
                    <th style="width: 45px; text-align: center;">Kode</th>
                    <th>Nama Kriteria Penilaian</th>
                    <th style="width: 90px; text-align: center;">Tipe Atribut</th>
                    <th style="text-align: right; width: 85px;">Bobot ($w_j$)</th>
                    <th style="text-align: right; width: 75px;">Persentase</th>
                    <th>Target Solusi Ideal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($marcosData['criterias'] as $c)
                    <tr>
                        <td style="text-align: center; font-weight: 700;">{{ $c->code }}</td>
                        <td><strong>{{ $c->name }}</strong></td>
                        <td style="text-align: center;">
                            <span style="font-weight: 600; text-transform: uppercase; font-size: 7.5pt; color: {{ $c->type === 'benefit' ? '#1E6B52' : '#8C6B28' }};">
                                {{ $c->type }}
                            </span>
                        </td>
                        <td style="text-align: right; font-family: monospace; font-weight: 600;">
                            {{ number_format((float) $c->weight, 4) }}
                        </td>
                        <td style="text-align: right; font-family: monospace;">
                            {{ number_format((float) $c->weight * 100, 1) }}%
                        </td>
                        <td>
                            {{ $c->type === 'benefit' ? 'Maksimal (Nilai evaluasi tertinggi diutamakan)' : 'Minimal (Biaya terendah diutamakan)' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- 5. TABEL 3: MATRIKS TERBOBOT (V) -->
        <h2 class="section-header">3. Matriks Normalisasi Terbobot ($V = N \times w_j$)</h2>
        <table class="formal-table">
            <thead>
                <tr>
                    <th>Paket Alternatif</th>
                    @foreach($marcosData['criterias'] as $c)
                        <th style="text-align: right; font-size: 7.5pt;">{{ $c->code }}</th>
                    @endforeach
                    <th style="text-align: right; width: 65px;">Total $S_i$</th>
                </tr>
            </thead>
            <tbody>
                @foreach($marcosData['bundlings'] as $b)
                    <tr>
                        <td>{{ $b->package_name }}</td>
                        @php $sumV = 0; @endphp
                        @foreach($marcosData['criterias'] as $c)
                            @php
                                $val = $marcosData['weighted'][$b->id][$c->id] ?? 0;
                                $sumV += $val;
                            @endphp
                            <td style="text-align: right; font-family: monospace; font-size: 7.8pt;">
                                {{ number_format($val, 4) }}
                            </td>
                        @endforeach
                        <td style="text-align: right; font-family: monospace; font-weight: 700;">
                            {{ number_format($sumV, 4) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- 6. LEMBAR PENGESAHAN / TANDA TANGAN -->
        <div class="signature-section">
            <div class="sig-box">
                <div>Mengetahui,</div>
                <div style="font-weight: 600;">Kepala Divisi Kurasi Vendor</div>
                <div class="sig-space"></div>
                <div class="sig-name">( Salsabila Siregar, S.E. )</div>
                <div style="font-size: 8pt; color: #6B7280;">NIP. GG-2024-0041</div>
            </div>

            <div class="sig-box">
                <div>Medan, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}</div>
                <div style="font-weight: 600;">Administrator Sistem SPK MARCOS</div>
                <div class="sig-space"></div>
                <div class="sig-name">( {{ auth()->user()->name ?? 'Administrator' }} )</div>
                <div style="font-size: 8pt; color: #6B7280;">ID Akun: {{ auth()->user()->email ?? 'admin@glowgrace.com' }}</div>
            </div>
        </div>
    </div>

</body>
</html>

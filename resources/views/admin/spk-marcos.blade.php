@extends('layouts.app')

@section('title', 'Laporan Analisis SPK MARCOS - Admin Panel')

@section('content')
<div class="container-wide" style="padding-top: 36px; padding-bottom: 64px;">
    <!-- Admin Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 28px;">
        <div>
            <span class="badge badge-accent" style="margin-bottom: 6px;">Laporan Komputasi Ilmiah</span>
            <h1 class="headline-lg">Laporan Lengkap Komputasi SPK MARCOS</h1>
            <p class="body-sm">Hasil kalkulasi matematis algoritma MARCOS untuk perankingan paket make-up & dekorasi pelaminan.</p>
        </div>
        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
            <!-- View Data Excel Button -->
            <button type="button" class="btn btn-secondary btn-sm" onclick="openMarcosExcelModal()" title="Buka tampilan spreadsheet Excel interaktif">
                <span class="material-symbols-outlined" style="font-size: 16px; color: #107C41;">table_view</span>
                <span>Lihat Data Excel</span>
            </button>

            <!-- Export Excel Button -->
            <a href="{{ route('admin.spk.export-excel') }}" class="btn btn-secondary btn-sm" title="Unduh hasil laporan dalam format Excel (.csv)">
                <span class="material-symbols-outlined" style="font-size: 16px; color: #107C41;">file_download</span>
                <span>Ekspor Excel (.csv)</span>
            </a>

            <!-- Quick Import Shortcut -->
            <a href="{{ route('admin.evaluations') }}" class="btn btn-secondary btn-sm" title="Kelola / Import nilai evaluasi baru">
                <span class="material-symbols-outlined" style="font-size: 16px; color: var(--primary);">upload_file</span>
                <span>Import Skor Baru</span>
            </a>

            <a href="{{ route('admin.spk.print-pdf') }}" target="_blank" class="btn btn-secondary btn-sm" title="Buka cetak laporan resmi A4 dengan kop surat Glow & Grace">
                <i data-lucide="printer" style="width: 14px; height: 14px;"></i>
                <span>Cetak PDF Laporan Resmi</span>
            </a>
        </div>
    </div>

    <!-- Admin Navigation Chips -->
    <div style="display: flex; gap: 10px; margin-bottom: 32px; border-bottom: 1px solid var(--color-border); padding-bottom: 12px; overflow-x: auto;">
        <a href="{{ route('admin.dashboard') }}" class="chip">Ringkasan</a>
        <a href="{{ route('admin.criterias') }}" class="chip">Kriteria & Bobot (C1-C7)</a>
        <a href="{{ route('admin.evaluations') }}" class="chip">Matriks Evaluasi Alternatif</a>
        <a href="{{ route('admin.orders') }}" class="chip">Pesanan & Pembayaran</a>
        <a href="{{ route('admin.spk') }}" class="chip active">Laporan Lengkap MARCOS</a>
        <a href="{{ route('admin.users') }}" class="chip">Pengguna & Vendor</a>
    </div>

    <!-- 1. Ranking Akhir -->
    <div class="card-dribbble" style="margin-bottom: 32px; padding: 24px; border-radius: 12px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <div style="display: flex; align-items: center;">
                <h2 class="headline-sm">1. Tabel Peringkat Rekomendasi Akhir</h2>
                <button type="button" class="btn-formula-info" onclick="toggleFormula('f-adm-rank')" title="Klik untuk melihat rumus matematis">!</button>
            </div>
            <span class="badge badge-accent">Metode MARCOS</span>
        </div>

        <div id="f-adm-rank" class="formula-card-popup">
            <div style="font-weight: 700; margin-bottom: 4px;">Formulasi Fungsi Utilitas $f(K_i)$:</div>
            <div class="formula-math">
                f(K_i) = \frac{K_i^+ + K_i^-}{1 + \frac{1 - f(K_i^+)}{f(K_i^+)} + \frac{1 - f(K_i^-)}{f(K_i^-)}}
            </div>
            <div class="caption">Alternatif diurutkan dari skor $f(K_i)$ tertinggi ke terendah sebagai solusi kompromi optimal.</div>
        </div>

        <div class="table-responsive">
            <table class="table-dribbble">
                <thead>
                    <tr>
                        <th style="width: 70px; text-align: center;">Peringkat</th>
                        <th>Nama Paket Bundling</th>
                        <th>Vendor</th>
                        <th>Harga</th>
                        <th>Total Nilai Terbobot</th>
                        <th>Nilai Utilitas Anti-Ideal</th>
                        <th>Nilai Utilitas Ideal</th>
                        <th>Nilai Rekomendasi Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($marcosData['rankings'] ?? [] as $r)
                        <tr style="{{ $r['rank'] == 1 ? 'background: #FFF9F2;' : '' }}">
                            <td style="text-align: center;">
                                <span class="badge {{ $r['rank'] == 1 ? 'badge-accent' : ($r['rank'] <= 3 ? 'badge-primary' : 'badge-muted') }}">
                                    #{{ $r['rank'] }}
                                </span>
                            </td>
                            <td><strong>{{ $r['package_name'] }}</strong></td>
                            <td>{{ $r['vendor_name'] }}</td>
                            <td>Rp {{ number_format($r['price'], 0, ',', '.') }}</td>
                            <td><code>{{ number_format($r['S_i'], 4) }}</code></td>
                            <td><code>{{ number_format($r['K_minus'], 4) }}</code></td>
                            <td><code>{{ number_format($r['K_plus'], 4) }}</code></td>
                            <td>
                                <strong style="color: var(--color-highlight); font-size: 13.5px;">
                                    {{ number_format($r['final_score'], 5) }}
                                </strong>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- 2. Solusi Ideal & Anti-Ideal -->
    <div class="card-quiet" style="margin-bottom: 32px; padding: 24px; border-radius: 12px;">
        <div style="display: flex; align-items: center; margin-bottom: 12px;">
            <h2 class="headline-sm">2. Solusi Ideal & Solusi Anti-Ideal</h2>
            <button type="button" class="btn-formula-info" onclick="toggleFormula('f-adm-ideal')" title="Klik untuk melihat rumus matematis">!</button>
        </div>

        <div id="f-adm-ideal" class="formula-card-popup">
            <div style="font-weight: 700; margin-bottom: 4px;">Formulasi Titik Acual Ideal & Anti-Ideal:</div>
            <div class="formula-math">
                \text{Benefit: } A^+ = \max_{i} x_{ij}, \quad A^- = \min_{i} x_{ij} \\
                \text{Cost: } A^+ = \min_{i} x_{ij}, \quad A^- = \max_{i} x_{ij}
            </div>
        </div>

        <div class="table-responsive">
            <table class="table-dribbble">
                <thead>
                    <tr>
                        <th>Parameter</th>
                        @foreach($marcosData['criterias'] ?? [] as $c)
                            <th style="text-align: center;">{{ $c->code }} ({{ ucfirst($c->type) }})</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Bobot Kriteria</strong></td>
                        @foreach($marcosData['criterias'] ?? [] as $c)
                            <td style="text-align: center;"><code>{{ number_format($c->weight, 4) }}</code></td>
                        @endforeach
                    </tr>
                    <tr>
                        <td><strong>Nilai Solusi Ideal</strong></td>
                        @foreach($marcosData['criterias'] ?? [] as $c)
                            <td style="text-align: center;"><span class="badge badge-primary">{{ $marcosData['ideal'][$c->id] ?? '-' }}</span></td>
                        @endforeach
                    </tr>
                    <tr>
                        <td><strong>Nilai Solusi Anti-Ideal</strong></td>
                        @foreach($marcosData['criterias'] ?? [] as $c)
                            <td style="text-align: center;"><span class="badge badge-muted">{{ $marcosData['anti_ideal'][$c->id] ?? '-' }}</span></td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 2.5 Matriks Keputusan Awal (X) -->
    <div class="card-dribbble" style="margin-bottom: 32px; padding: 24px; border-radius: 12px;">
        <div style="display: flex; align-items: center; margin-bottom: 12px;">
            <h2 class="headline-sm">3. Matriks Keputusan Awal ($X$)</h2>
            <button type="button" class="btn-formula-info" onclick="toggleFormula('f-adm-x')" title="Klik untuk melihat rumus matematis">!</button>
        </div>
        <p class="caption" style="margin-bottom: 14px;">Nilai skor evaluasi alternatif untuk masing-masing kriteria (skor 1, 3, atau 5).</p>

        <div id="f-adm-x" class="formula-card-popup">
            <div style="font-weight: 700; margin-bottom: 4px;">Formulasi Matriks Keputusan ($X$):</div>
            <div class="formula-math">
                X = \begin{bmatrix} x_{11} & x_{12} & \cdots & x_{1n} \\ x_{21} & x_{22} & \cdots & x_{2n} \\ \vdots & \vdots & \ddots & \vdots \\ x_{m1} & x_{m2} & \cdots & x_{mn} \end{bmatrix}
            </div>
            <div class="caption">Matriks $X$ berukuran $m \times n$, di mana $m$ adalah jumlah alternatif dan $n$ adalah jumlah kriteria (C1-C7).</div>
        </div>

        <div class="table-responsive">
            <table class="table-dribbble">
                <thead>
                    <tr>
                        <th>Paket Alternatif</th>
                        @foreach($marcosData['criterias'] ?? [] as $c)
                            <th style="text-align: center;">{{ $c->code }} ({{ ucfirst($c->type) }})</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($marcosData['bundlings'] ?? [] as $b)
                        <tr>
                            <td><strong>{{ $b->package_name }}</strong></td>
                            @foreach($marcosData['criterias'] ?? [] as $c)
                                <td style="text-align: center;">
                                    <code>{{ $marcosData['matrix'][$b->id][$c->id] ?? 1 }}</code>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- 3. Matriks Ternormalisasi (N) -->
    <div class="card-dribbble" style="margin-bottom: 32px; padding: 24px; border-radius: 12px;">
        <div style="display: flex; align-items: center; margin-bottom: 12px;">
            <h2 class="headline-sm">4. Matriks Ternormalisasi ($N$)</h2>
            <button type="button" class="btn-formula-info" onclick="toggleFormula('f-adm-norm')" title="Klik untuk melihat rumus matematis">!</button>
        </div>

        <div id="f-adm-norm" class="formula-card-popup">
            <div style="font-weight: 700; margin-bottom: 4px;">Formulasi Matriks Ternormalisasi:</div>
            <div class="formula-math">
                n_{ij} = \frac{x_{ij}}{x_j^+} \text{ (Benefit)}, \qquad n_{ij} = \frac{x_j^+}{x_{ij}} \text{ (Cost)}
            </div>
        </div>

        <div class="table-responsive">
            <table class="table-dribbble">
                <thead>
                    <tr>
                        <th>Paket Alternatif</th>
                        @foreach($marcosData['criterias'] ?? [] as $c)
                            <th style="text-align: center;">{{ $c->code }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($marcosData['bundlings'] ?? [] as $b)
                        <tr>
                            <td><strong>{{ $b->package_name }}</strong></td>
                            @foreach($marcosData['criterias'] ?? [] as $c)
                                <td style="text-align: center;">
                                    <code>{{ number_format($marcosData['normalized'][$b->id][$c->id] ?? 0, 4) }}</code>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- 5. Matriks Terbobot & Total Nilai Terbobot (V) -->
    <div class="card-dribbble" style="padding: 24px; border-radius: 12px;">
        <div style="display: flex; align-items: center; margin-bottom: 12px;">
            <h2 class="headline-sm">5. Matriks Terbobot ($V$) & Total Nilai Terbobot ($S_i$)</h2>
            <button type="button" class="btn-formula-info" onclick="toggleFormula('f-adm-weighted')" title="Klik untuk melihat rumus matematis">!</button>
        </div>

        <div id="f-adm-weighted" class="formula-card-popup">
            <div style="font-weight: 700; margin-bottom: 4px;">Formulasi Matriks Terbobot:</div>
            <div class="formula-math">
                v_{ij} = n_{ij} \times w_j, \qquad S_i = \sum_{j=1}^n v_{ij}
            </div>
        </div>

        <div class="table-responsive">
            <table class="table-dribbble">
                <thead>
                    <tr>
                        <th>Paket Alternatif</th>
                        @foreach($marcosData['criterias'] ?? [] as $c)
                            <th style="text-align: center;">{{ $c->code }}</th>
                        @endforeach
                        <th style="text-align: center;">Total Nilai Terbobot</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($marcosData['bundlings'] ?? [] as $b)
                        @php
                            $si = array_sum($marcosData['weighted'][$b->id] ?? []);
                        @endphp
                        <tr>
                            <td><strong>{{ $b->package_name }}</strong></td>
                            @foreach($marcosData['criterias'] ?? [] as $c)
                                <td style="text-align: center;">
                                    <code>{{ number_format($marcosData['weighted'][$b->id][$c->id] ?? 0, 4) }}</code>
                                </td>
                            @endforeach
                            <td style="text-align: center;">
                                <strong>{{ number_format($si, 4) }}</strong>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ==========================================================================
     MODAL: VIEW DATA MARCOS DALAM BENTUK EXCEL (INTERACTIVE SPREADSHEET)
     ========================================================================== -->
<div id="marcosExcelModal" class="excel-modal-backdrop" role="dialog" aria-modal="true">
    <div class="excel-window">
        <!-- Excel Title Bar -->
        <div class="excel-titlebar">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span class="material-symbols-outlined" style="font-size: 18px;">grid_on</span>
                <span>Microsoft Excel - Laporan_Komputasi_MARCOS.xlsx [Workbook Ilmiah SPK]</span>
            </div>
            <button type="button" onclick="closeMarcosExcelModal()" style="background: none; border: none; color: #FFFFFF; font-size: 18px; cursor: pointer; padding: 0 4px;" title="Tutup">
                &times;
            </button>
        </div>

        <!-- Excel Ribbon Menu -->
        <div class="excel-ribbon">
            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                <span class="badge" style="background: #107C41; color: #FFFFFF; font-size: 11px;">MARCOS Analytics Active</span>
                <button type="button" class="btn btn-secondary btn-sm" onclick="exportMarcosXlsx()" style="height: 28px; font-size: 11px; border-color: #107C41; color: #107C41;">
                    <span class="material-symbols-outlined" style="font-size: 14px;">file_download</span>
                    Unduh Workbook (.xlsx)
                </button>
                <a href="{{ route('admin.spk.export-excel') }}" class="btn btn-secondary btn-sm" style="height: 28px; font-size: 11px;">
                    <span class="material-symbols-outlined" style="font-size: 14px;">download</span>
                    Unduh Format .csv
                </a>
            </div>
            <div class="caption" style="color: #605E5C;">
                Perangkingan: <strong style="color: #107C41;">{{ count($marcosData['rankings'] ?? []) }}</strong> Alternatif Terbaik
            </div>
        </div>

        <!-- Excel Formula Bar -->
        <div class="excel-formula-bar">
            <div class="excel-formula-name" id="marcosActiveCellLabel">H2</div>
            <span style="color: #A19F9D; font-weight: bold;">fx</span>
            <input type="text" id="marcosFormulaInput" class="excel-formula-input" value="=MARCOS_UTILITY(Si, SAI, SAAI)" readonly>
        </div>

        <!-- Excel Grid Container -->
        <div class="excel-grid-container">
            <table class="excel-table" id="marcosExcelTable">
                <thead>
                    <tr>
                        <th class="excel-row-header"></th>
                        <th>A<br><span style="font-size: 10px; font-weight: normal;">Rank</span></th>
                        <th>B<br><span style="font-size: 10px; font-weight: normal;">Nama Paket</span></th>
                        <th>C<br><span style="font-size: 10px; font-weight: normal;">Vendor</span></th>
                        <th>D<br><span style="font-size: 10px; font-weight: normal;">Harga (Rp)</span></th>
                        <th>E<br><span style="font-size: 10px; font-weight: normal;">S_i (Terbobot)</span></th>
                        <th>F<br><span style="font-size: 10px; font-weight: normal;">K^- (Anti-Ideal)</span></th>
                        <th>G<br><span style="font-size: 10px; font-weight: normal;">K^+ (Ideal)</span></th>
                        <th>H<br><span style="font-size: 10px; font-weight: normal;">f(K_i) Skor Akhir</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($marcosData['rankings'] ?? [] as $idx => $r)
                        <tr style="{{ $r['rank'] == 1 ? 'background: #FAF8EE;' : '' }}">
                            <th class="excel-row-header">{{ $idx + 1 }}</th>
                            <td class="excel-cell" data-coord="A{{ $idx + 1 }}" style="text-align: center; font-weight: 700; color: {{ $r['rank'] == 1 ? '#8C6B28' : '#323130' }};">
                                #{{ $r['rank'] }}
                            </td>
                            <td class="excel-cell" data-coord="B{{ $idx + 1 }}" style="font-weight: 600;">{{ $r['package_name'] }}</td>
                            <td class="excel-cell" data-coord="C{{ $idx + 1 }}">{{ $r['vendor_name'] }}</td>
                            <td class="excel-cell" data-coord="D{{ $idx + 1 }}" style="text-align: right;">{{ number_format($r['price'], 0, ',', '.') }}</td>
                            <td class="excel-cell" data-coord="E{{ $idx + 1 }}" style="text-align: right;">{{ number_format($r['S_i'], 4) }}</td>
                            <td class="excel-cell" data-coord="F{{ $idx + 1 }}" style="text-align: right;">{{ number_format($r['K_minus'], 4) }}</td>
                            <td class="excel-cell" data-coord="G{{ $idx + 1 }}" style="text-align: right;">{{ number_format($r['K_plus'], 4) }}</td>
                            <td class="excel-cell" data-coord="H{{ $idx + 1 }}" style="text-align: right; font-weight: 700; color: #107C41;">
                                {{ number_format($r['final_score'], 5) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Excel Sheet Footer -->
        <div class="excel-sheet-footer">
            <div style="display: flex; gap: 4px;">
                <div class="excel-sheet-tab">
                    <span class="material-symbols-outlined" style="font-size: 14px;">military_tech</span>
                    Sheet1 - Peringkat Rekomendasi
                </div>
            </div>
            <button type="button" class="btn btn-secondary btn-sm" onclick="closeMarcosExcelModal()">
                Tutup Spreadsheet
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleFormula(id) {
        const el = document.getElementById(id);
        if (el) {
            el.classList.toggle('show');
        }
    }

    function openMarcosExcelModal() {
        const modal = document.getElementById('marcosExcelModal');
        if (modal) {
            modal.style.display = 'flex';
            modal.offsetHeight;
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeMarcosExcelModal() {
        const modal = document.getElementById('marcosExcelModal');
        if (modal) {
            modal.classList.remove('active');
            setTimeout(() => {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }, 250);
        }
    }

    function exportMarcosXlsx() {
        if (typeof XLSX === 'undefined') {
            window.location.href = "{{ route('admin.spk.export-excel') }}";
            return;
        }
        const table = document.getElementById('marcosExcelTable');
        const wb = XLSX.utils.table_to_book(table, { sheet: "Ranking_MARCOS" });
        XLSX.writeFile(wb, "Hasil_Perhitungan_MARCOS_" + new Date().toISOString().slice(0,10) + ".xlsx");
    }

    document.querySelectorAll('#marcosExcelTable .excel-cell').forEach(cell => {
        cell.addEventListener('click', function() {
            document.querySelectorAll('#marcosExcelTable .excel-cell').forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            const coord = this.getAttribute('data-coord');
            const val = this.innerText.trim();
            document.getElementById('marcosActiveCellLabel').innerText = coord;
            document.getElementById('marcosFormulaInput').value = val;
        });
    });
</script>
@endpush
@endsection

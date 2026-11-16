@extends('layouts.app')

@section('title', 'Matriks Evaluasi Alternatif - Admin Panel SPK')

@section('content')
<div class="container-wide" style="padding-top: 36px; padding-bottom: 64px;">
    <!-- Admin Header & Action Toolbar -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 28px;">
        <div>
            <span class="badge badge-accent" style="margin-bottom: 6px;">Input Matriks SPK MARCOS</span>
            <h1 class="headline-lg">Evaluasi Skor Alternatif (X)</h1>
            <p class="body-sm">Kelola nilai konversi skala Likert/kategori (1, 3, 5) untuk setiap paket bundling terhadap 7 kriteria.</p>
        </div>
        
        <!-- Excel Action Buttons Toolbar -->
        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <!-- View Data Excel Button -->
            <button type="button" class="btn btn-secondary" onclick="openExcelViewerModal()" title="Buka tampilan spreadsheet Excel interaktif">
                <span class="material-symbols-outlined" style="font-size: 18px; color: #107C41;">table_view</span>
                <span>Lihat Data Excel</span>
            </button>

            <!-- Import Excel Button -->
            <button type="button" class="btn btn-secondary" onclick="openExcelImportModal()" title="Unggah file Excel untuk menghitung MARCOS">
                <span class="material-symbols-outlined" style="font-size: 18px; color: #107C41;">upload_file</span>
                <span>Import Excel</span>
            </button>

            <!-- Download Template Excel Button -->
            <a href="{{ route('admin.evaluations.template-excel') }}" class="btn btn-secondary btn-sm" title="Unduh template format file Excel (.csv)">
                <span class="material-symbols-outlined" style="font-size: 16px; color: var(--primary);">download</span>
                <span>Template Excel</span>
            </a>

            <!-- Clear / Hapus Perhitungan Evaluasi Button -->
            <button type="button" id="btnOpenClearEvaluations" class="btn btn-danger btn-sm" onclick="openClearEvaluationsModal()" style="background: #FEE2E2; color: #DC2626; border: 1px solid #FCA5A5; height: 38px; padding: 0 14px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;" title="Hapus seluruh data perhitungan evaluasi skor">
                <span class="material-symbols-outlined" style="font-size: 18px;">delete_forever</span>
                <span>Hapus Perhitungan</span>
            </button>
        </div>
    </div>



    <!-- Instruction Helper Card -->
    <div class="card-quiet" style="margin-bottom: 24px; border-radius: 12px; padding: 16px 20px;">
        <div style="display: flex; gap: 12px; align-items: center;">
            <span class="material-symbols-outlined" style="font-size: 22px; color: var(--primary-container); flex-shrink: 0;">info</span>
            <div class="body-sm">
                <strong>Pedoman Skala Penilaian Proposal:</strong> Gunakan skor konversi <code>1</code> (Rendah/Cukup), <code>3</code> (Bagus/Sedang), dan <code>5</code> (Istimewa/Mewah/Tinggi). Anda juga dapat langsung mengunggah file spreadsheet dengan tombol <strong>Import Excel</strong> di atas atau melihat tabel kalkulasi dengan <strong>Lihat Data Excel</strong>.
            </div>
        </div>
    </div>

    <form id="evaluationMatrixForm" action="{{ route('admin.evaluations.update') }}" method="POST">
        @csrf
        <div class="card-dribbble" style="padding: 24px; border-radius: 14px; margin-bottom: 24px;">
            <div class="table-responsive">
                <table class="table-dribbble" id="evaluationsTable">
                    <thead>
                        <tr>
                            <th style="min-width: 220px;">Paket Alternatif & Vendor</th>
                            @foreach($criterias as $c)
                                <th style="text-align: center; min-width: 110px;">
                                    <div>{{ $c->code }}</div>
                                    <div class="caption" style="font-weight: 500;">{{ $c->name }}</div>
                                    <span class="badge {{ $c->type === 'benefit' ? 'badge-success' : 'badge-amber' }}" style="font-size: 9px; padding: 2px 6px;">
                                        {{ ucfirst($c->type) }}
                                    </span>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bundlings as $b)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 2px;">
                                        @if(!empty($b->code))
                                            <span style="font-weight: 700; background: #0D0C22; color: #FFF; padding: 1px 6px; border-radius: 4px; font-size: 11px;">{{ $b->code }}</span>
                                        @endif
                                        <strong>{{ $b->package_name }}</strong>
                                    </div>
                                    <div class="caption">{{ $b->vendor->vendor_name ?? '-' }} &bull; Rp {{ number_format($b->base_price, 0, ',', '.') }}</div>
                                </td>
                                @foreach($criterias as $c)
                                    @php
                                        $eval = $b->evaluations->where('criteria_id', $c->id)->first();
                                        $currentScore = $eval ? $eval->score : 3;
                                    @endphp
                                    <td style="text-align: center;">
                                        <select name="scores[{{ $b->id }}][{{ $c->id }}]" 
                                                data-bundling-id="{{ $b->id }}"
                                                data-criteria-id="{{ $c->id }}"
                                                data-package="{{ $b->package_name }}"
                                                data-code="{{ $c->code }}"
                                                class="select-pill evaluation-score-select" 
                                                style="height: 38px; padding: 0 10px; font-size: 13px; text-align: center;">
                                            <option value="1" {{ $currentScore == 1 ? 'selected' : '' }}>1</option>
                                            <option value="2" {{ $currentScore == 2 ? 'selected' : '' }}>2</option>
                                            <option value="3" {{ $currentScore == 3 ? 'selected' : '' }}>3</option>
                                            <option value="4" {{ $currentScore == 4 ? 'selected' : '' }}>4</option>
                                            <option value="5" {{ $currentScore == 5 ? 'selected' : '' }}>5</option>
                                        </select>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
                <div class="caption">
                    Perubahan nilai evaluasi akan langsung memengaruhi perhitungan normalisasi dan peringkat rekomendasi MARCOS.
                </div>
                <button type="submit" class="btn btn-primary" style="height: 48px; padding: 0 32px; font-size: 14px;">
                    <i data-lucide="save" style="width: 16px; height: 16px;"></i>
                    Simpan Seluruh Skor Evaluasi
                </button>
            </div>
        </div>
    </form>
</div>

<!-- ==========================================================================
     MODAL 1: VIEW DATA DALAM BENTUK EXCEL (INTERACTIVE SPREADSHEET VIEWER)
     ========================================================================== -->
<div id="excelViewerModal" class="excel-modal-backdrop" role="dialog" aria-modal="true">
    <div class="excel-window">
        <!-- Excel Title Bar -->
        <div class="excel-titlebar">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span class="material-symbols-outlined" style="font-size: 18px;">grid_on</span>
                <span>Microsoft Excel - Matriks_Evaluasi_MARCOS.xlsx [Pratinjau Lembar Kerja]</span>
            </div>
            <button type="button" onclick="closeExcelViewerModal()" style="background: none; border: none; color: #FFFFFF; font-size: 18px; cursor: pointer; padding: 0 4px;" title="Tutup">
                &times;
            </button>
        </div>

        <!-- Excel Ribbon Menu -->
        <div class="excel-ribbon">
            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                <span class="badge" style="background: #107C41; color: #FFFFFF; font-size: 11px;">Excel Mode Aktif</span>
                <button type="button" class="btn btn-secondary btn-sm" onclick="exportCurrentExcelFile()" style="height: 28px; font-size: 11px; border-color: #107C41; color: #107C41;">
                    <span class="material-symbols-outlined" style="font-size: 14px;">file_download</span>
                    Unduh File .xlsx Asli
                </button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="exportCurrentCsvFile()" style="height: 28px; font-size: 11px;">
                    <span class="material-symbols-outlined" style="font-size: 14px;">download</span>
                    Unduh .csv
                </button>
                <label class="btn btn-secondary btn-sm" style="height: 28px; font-size: 11px; cursor: pointer; margin: 0;">
                    <span class="material-symbols-outlined" style="font-size: 14px;">folder_open</span>
                    Buka File Excel Lokal
                    <input type="file" id="localExcelInput" accept=".xlsx, .xls, .csv" style="display: none;" onchange="handleLocalExcelUpload(event)">
                </label>
            </div>
            <div class="caption" style="color: #605E5C;">
                Total Baris: <strong id="excelRowCount">{{ count($bundlings) }}</strong> Paket Alternatif
            </div>
        </div>

        <!-- Excel Formula Bar -->
        <div class="excel-formula-bar">
            <div class="excel-formula-name" id="excelActiveCellLabel">A1</div>
            <span style="color: #A19F9D; font-weight: bold;">fx</span>
            <input type="text" id="excelFormulaInput" class="excel-formula-input" value="=EVALUASI_MARCOS(C1:C7)" readonly>
        </div>

        <!-- Excel Grid Table -->
        <div class="excel-grid-container" id="excelGridContainer">
            <table class="excel-table" id="renderedExcelTable">
                <thead>
                    <tr>
                        <th class="excel-row-header"></th>
                        <th>A<br><span style="font-size: 10px; font-weight: normal;">ID</span></th>
                        <th>B<br><span style="font-size: 10px; font-weight: normal;">Nama Paket</span></th>
                        <th>C<br><span style="font-size: 10px; font-weight: normal;">Vendor</span></th>
                        <th>D<br><span style="font-size: 10px; font-weight: normal;">Harga (Rp)</span></th>
                        @foreach($criterias as $index => $c)
                            @php
                                $colLetter = chr(69 + $index); // E, F, G, H...
                            @endphp
                            <th>{{ $colLetter }}<br><span style="font-size: 10px; font-weight: normal;">{{ $c->code }}</span></th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($bundlings as $bIdx => $b)
                        <tr>
                            <th class="excel-row-header">{{ $bIdx + 1 }}</th>
                            <td class="excel-cell" data-coord="A{{ $bIdx + 1 }}">{{ $b->id }}</td>
                            <td class="excel-cell" data-coord="B{{ $bIdx + 1 }}" style="font-weight: 600;">{{ $b->package_name }}</td>
                            <td class="excel-cell" data-coord="C{{ $bIdx + 1 }}">{{ $b->vendor->vendor_name ?? '-' }}</td>
                            <td class="excel-cell" data-coord="D{{ $bIdx + 1 }}" style="text-align: right;">{{ number_format($b->base_price, 0, ',', '.') }}</td>
                            @foreach($criterias as $cIdx => $c)
                                @php
                                    $eval = $b->evaluations->where('criteria_id', $c->id)->first();
                                    $val = $eval ? $eval->score : 3;
                                    $coord = chr(69 + $cIdx) . ($bIdx + 1);
                                @endphp
                                <td class="excel-cell" data-coord="{{ $coord }}" style="text-align: center; font-weight: 600; color: {{ $val == 5 ? '#107C41' : ($val == 3 ? '#C5A059' : '#8A8886') }};">
                                    {{ $val }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Excel Sheet Footer Tabs -->
        <div class="excel-sheet-footer">
            <div style="display: flex; gap: 4px;">
                <div class="excel-sheet-tab">
                    <span class="material-symbols-outlined" style="font-size: 14px;">description</span>
                    Sheet1 - Matriks Keputusan (X)
                </div>
            </div>
            <button type="button" class="btn btn-secondary btn-sm" onclick="closeExcelViewerModal()">
                Tutup Spreadsheet
            </button>
        </div>
    </div>
</div>

<!-- ==========================================================================
     MODAL 2: IMPORT FILE EXCEL (.XLSX / .CSV) UNTUK MENGHITUNG MARCOS
     ========================================================================== -->
<div id="excelImportModal" class="modal-backdrop-luxury" style="display: none;" role="dialog" aria-modal="true">
    <div class="modal-box-luxury" style="max-width: 540px; text-align: left;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 36px; height: 36px; border-radius: 8px; background: #E8F4EC; display: flex; align-items: center; justify-content: center; color: #107C41;">
                    <span class="material-symbols-outlined" style="font-size: 20px;">upload_file</span>
                </div>
                <h3 class="modal-title-luxury" style="font-size: 19px; margin: 0;">Import File Excel Evaluasi MARCOS</h3>
            </div>
            <button type="button" onclick="closeExcelImportModal()" style="background: none; border: none; font-size: 22px; cursor: pointer; color: var(--muted);">&times;</button>
        </div>

        <p class="body-sm" style="margin-bottom: 18px; line-height: 1.55;">
            Unggah file spreadsheet <strong>.xlsx</strong> atau <strong>.csv</strong> yang berisi nilai evaluasi kriteria (C1 s/d C7). Nilai skor akan otomatis diperbarui dan perankingan MARCOS dihitung ulang.
        </p>

        <form action="{{ route('admin.evaluations.import-excel') }}" method="POST" enctype="multipart/form-data" id="excelUploadBackendForm">
            @csrf
            <!-- Drag & Drop Zone -->
            <div id="excelDropZone" style="border: 2px dashed rgba(197, 160, 89, 0.45); border-radius: 12px; background: #FAF8F5; padding: 24px 16px; text-align: center; margin-bottom: 18px; cursor: pointer; transition: all 0.2s;">
                <span class="material-symbols-outlined" style="font-size: 38px; color: #107C41; margin-bottom: 6px;">cloud_upload</span>
                <div style="font-weight: 600; color: var(--emerald-deep); font-size: 14px;">Klik untuk memilih file Excel atau tarik ke sini</div>
                <div class="caption" style="color: var(--muted); margin-top: 4px;">Mendukung format .xlsx, .xls, dan .csv (Maksimal 5MB)</div>
                <input type="file" name="excel_file" id="excelFileInput" accept=".xlsx, .xls, .csv" style="display: none;" onchange="handleExcelFileSelected(event)">
                <div id="selectedFileNameDisplay" style="margin-top: 10px; font-size: 12px; font-weight: 600; color: #107C41; display: none;"></div>
            </div>

            <!-- Preview Card if file parsed via SheetJS -->
            <div id="excelParsedPreview" style="display: none; margin-bottom: 16px; max-height: 180px; overflow-y: auto; border: 1px solid var(--border-gold-subtle); border-radius: 8px; padding: 10px; background: #FFFFFF; font-size: 11.5px;">
                <div style="font-weight: 600; color: var(--emerald-deep); margin-bottom: 6px;">Pratinjau Data yang Terbaca:</div>
                <div id="excelParsedRowsCount" class="caption" style="color: var(--muted); margin-bottom: 6px;"></div>
                <div id="excelParsedTableContainer"></div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
                <a href="{{ route('admin.evaluations.template-excel') }}" class="btn btn-tertiary" style="font-size: 12px; color: var(--primary);">
                    <span class="material-symbols-outlined" style="font-size: 15px;">download</span>
                    Unduh Format Template
                </a>
                <div style="display: flex; gap: 8px;">
                    <button type="button" class="btn btn-secondary" onclick="closeExcelImportModal()">Batal</button>
                    <button type="submit" id="btnSubmitExcel" class="btn btn-primary" style="background: #107C41; border-color: #107C41;">
                        <span class="material-symbols-outlined" style="font-size: 16px;">save</span>
                        Proses & Hitung MARCOS
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ==========================================================================
     MODAL 3: HAPUS EVALUASI SKOR (TWO-STEP VERIFICATION MODAL)
     ========================================================================== -->
<div id="clearEvaluationsModal" role="dialog" aria-modal="true" style="display: none; position: fixed; inset: 0; z-index: 99999; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #FFFFFF; border-radius: 16px; width: 95%; max-width: 520px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); border: 1px solid #E2E8F0; overflow: hidden; animation: popIn 0.2s ease-out;">
        
        <!-- STEP 1: Konfirmasi Awal -->
        <div id="clearStep1" style="padding: 28px 24px;">
            <div style="display: flex; align-items: flex-start; gap: 14px; margin-bottom: 18px;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: #FEE2E2; border: 1px solid #FECACA; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #DC2626;">
                    <span class="material-symbols-outlined" style="font-size: 26px;">warning</span>
                </div>
                <div>
                    <h3 style="font-family: var(--font-heading); font-size: 1.2rem; font-weight: 700; color: #1E293B; margin-bottom: 4px;">
                        Hapus Seluruh Evaluasi Skor?
                    </h3>
                    <div style="font-size: 11.5px; color: #DC2626; font-weight: 600;">
                        Langkah 1 dari 2: Konfirmasi Awal
                    </div>
                </div>
            </div>

            <p style="font-size: 13.5px; line-height: 1.6; color: #475569; margin-bottom: 24px;">
                Apakah Anda yakin ingin menghapus seluruh data evaluasi skor alternatif ini? Tindakan ini akan mengosongkan seluruh matriks keputusan ($X$) dan perhitungan rekomendasi SPK MARCOS pada sistem.
            </p>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-secondary" onclick="closeClearEvaluationsModal()" style="cursor: pointer;">
                    Batal
                </button>
                <button type="button" class="btn btn-danger" onclick="goToClearStep2()" style="background: #DC2626; border-color: #DC2626; color: #FFF; font-weight: 600; cursor: pointer;">
                    Lanjut
                </button>
            </div>
        </div>

        <!-- STEP 2: Verifikasi Keamanan Cadangan File Excel -->
        <div id="clearStep2" style="display: none; padding: 28px 24px;">
            <div style="display: flex; align-items: flex-start; gap: 14px; margin-bottom: 18px;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: #FEF3C7; border: 1px solid #FDE68A; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #B45309;">
                    <span class="material-symbols-outlined" style="font-size: 26px;">shield_lock</span>
                </div>
                <div>
                    <h3 style="font-family: var(--font-heading); font-size: 1.2rem; font-weight: 700; color: #1E293B; margin-bottom: 4px;">
                        Verifikasi Keamanan Kedua
                    </h3>
                    <div style="font-size: 11.5px; color: #B45309; font-weight: 600;">
                        Langkah 2 dari 2: Konfirmasi Cadangan File Excel
                    </div>
                </div>
            </div>

            <div style="background: #FFFBEB; border: 1px solid #FCD34D; border-radius: 10px; padding: 14px 16px; margin-bottom: 20px;">
                <p style="font-size: 13px; line-height: 1.6; color: #92400E; margin: 0;">
                    <strong>Peringatan Penting:</strong> Untuk menghapus ini, Anda disarankan/harus menyimpan file Excel perhitungan yang sudah ada di sistem agar tidak terjadi hal yang tidak diinginkan kedepannya. Apakah Anda ingin menyimpan atau sudah menyimpan perhitungan yang sudah ada sebelumnya?
                </p>
            </div>

            <div style="margin-bottom: 24px;">
                <a href="{{ route('admin.evaluations.template-excel') }}" class="btn btn-secondary btn-sm" style="width: 100%; justify-content: center; height: 40px; border-color: #107C41; color: #107C41; font-weight: 600; gap: 6px; display: inline-flex; align-items: center;" title="Unduh file backup excel saat ini">
                    <span class="material-symbols-outlined" style="font-size: 18px;">file_download</span>
                    <span>Unduh Cadangan Excel Saat Ini (.csv)</span>
                </a>
            </div>

            <form action="{{ route('admin.evaluations.clear') }}" method="POST">
                @csrf
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 10px; border-top: 1px solid #E2E8F0; padding-top: 18px;">
                    <button type="button" class="btn btn-secondary btn-sm" onclick="goToClearStep1()" style="cursor: pointer;">
                        &larr; Kembali
                    </button>
                    <div style="display: flex; gap: 8px;">
                        <button type="button" class="btn btn-secondary btn-sm" onclick="closeClearEvaluationsModal()" style="cursor: pointer;">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-danger btn-sm" style="background: #DC2626; border-color: #DC2626; color: #FFF; font-weight: 600; cursor: pointer;">
                            Saya Sudah Simpan, Tetap Hapus
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

@push('scripts')
<script>
    // Cell Click Interaction in Excel Viewer
    document.querySelectorAll('.excel-cell').forEach(cell => {
        cell.addEventListener('click', function() {
            document.querySelectorAll('.excel-cell').forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            const coord = this.getAttribute('data-coord');
            const val = this.innerText.trim();
            document.getElementById('excelActiveCellLabel').innerText = coord;
            document.getElementById('excelFormulaInput').value = val;
        });
    });

    function openExcelViewerModal() {
        const modal = document.getElementById('excelViewerModal');
        if (modal) {
            modal.style.display = 'flex';
            modal.offsetHeight;
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeExcelViewerModal() {
        const modal = document.getElementById('excelViewerModal');
        if (modal) {
            modal.classList.remove('active');
            setTimeout(() => {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }, 250);
        }
    }

    function openExcelImportModal() {
        const modal = document.getElementById('excelImportModal');
        if (modal) {
            modal.style.display = 'flex';
            modal.offsetHeight;
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeExcelImportModal() {
        const modal = document.getElementById('excelImportModal');
        if (modal) {
            modal.classList.remove('active');
            setTimeout(() => {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }, 250);
        }
    }

    // Two-Step Verification Modal for Clearing Evaluations
    function openClearEvaluationsModal() {
        const modal = document.getElementById('clearEvaluationsModal');
        if (modal) {
            document.getElementById('clearStep1').style.display = 'block';
            document.getElementById('clearStep2').style.display = 'none';
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }

    function closeClearEvaluationsModal() {
        const modal = document.getElementById('clearEvaluationsModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    function goToClearStep2() {
        document.getElementById('clearStep1').style.display = 'none';
        document.getElementById('clearStep2').style.display = 'block';
    }

    function goToClearStep1() {
        document.getElementById('clearStep2').style.display = 'none';
        document.getElementById('clearStep1').style.display = 'block';
    }

    document.addEventListener('DOMContentLoaded', function() {
        const btnClear = document.getElementById('btnOpenClearEvaluations');
        if (btnClear) {
            btnClear.addEventListener('click', function(e) {
                e.preventDefault();
                openClearEvaluationsModal();
            });
        }
    });

    // Drag and Drop Setup
    const dropZone = document.getElementById('excelDropZone');
    const fileInput = document.getElementById('excelFileInput');

    if (dropZone && fileInput) {
        dropZone.addEventListener('click', () => fileInput.click());
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = '#107C41';
            dropZone.style.background = '#E8F4EC';
        });
        dropZone.addEventListener('dragleave', () => {
            dropZone.style.borderColor = 'rgba(197, 160, 89, 0.45)';
            dropZone.style.background = '#FAF8F5';
        });
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = 'rgba(197, 160, 89, 0.45)';
            dropZone.style.background = '#FAF8F5';
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                handleExcelFileSelected({ target: fileInput });
            }
        });
    }

    // Handle File Selection with SheetJS parse
    function handleExcelFileSelected(event) {
        const file = event.target.files[0];
        if (!file) return;

        const display = document.getElementById('selectedFileNameDisplay');
        if (display) {
            display.innerText = 'File Terpilih: ' + file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
            display.style.display = 'block';
        }

        // Parse with SheetJS if available
        if (typeof XLSX !== 'undefined') {
            const reader = new FileReader();
            reader.onload = function(e) {
                try {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, { type: 'array' });
                    const firstSheetName = workbook.SheetNames[0];
                    const worksheet = workbook.Sheets[firstSheetName];
                    const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

                    if (jsonData && jsonData.length > 1) {
                        renderParsedPreview(jsonData);
                    }
                } catch (err) {
                    console.error('SheetJS parse error:', err);
                }
            };
            reader.readAsArrayBuffer(file);
        }
    }

    function renderParsedPreview(rows) {
        const container = document.getElementById('excelParsedPreview');
        const countDisplay = document.getElementById('excelParsedRowsCount');
        const tableContainer = document.getElementById('excelParsedTableContainer');

        if (!container || !rows.length) return;

        countDisplay.innerText = 'Ditemukan ' + (rows.length - 1) + ' baris data dari spreadsheet.';
        
        let html = '<table style="width: 100%; border-collapse: collapse; font-size: 11px;">';
        rows.slice(0, 5).forEach((row, rIdx) => {
            html += '<tr style="' + (rIdx === 0 ? 'background: #F3F2F1; font-weight: bold;' : 'border-bottom: 1px solid #E1DFDD;') + '">';
            row.forEach(cell => {
                html += '<td style="padding: 4px 6px;">' + (cell !== undefined ? cell : '') + '</td>';
            });
            html += '</tr>';
        });
        html += '</table>';
        if (rows.length > 5) {
            html += '<div style="color: #6E6D7A; font-style: italic; margin-top: 4px;">... dan ' + (rows.length - 5) + ' baris lainnya.</div>';
        }

        tableContainer.innerHTML = html;
        container.style.display = 'block';
    }

    // SheetJS Export Native XLSX
    function exportCurrentExcelFile() {
        if (typeof XLSX === 'undefined') {
            window.location.href = "{{ route('admin.evaluations.template-excel') }}";
            return;
        }
        const table = document.getElementById('renderedExcelTable');
        const wb = XLSX.utils.table_to_book(table, { sheet: "Matriks_Evaluasi" });
        XLSX.writeFile(wb, "Matriks_Evaluasi_MARCOS_" + new Date().toISOString().slice(0,10) + ".xlsx");
    }

    function exportCurrentCsvFile() {
        window.location.href = "{{ route('admin.evaluations.template-excel') }}";
    }

    // Local Excel upload to view inside the spreadsheet modal
    function handleLocalExcelUpload(event) {
        const file = event.target.files[0];
        if (!file || typeof XLSX === 'undefined') return;

        const reader = new FileReader();
        reader.onload = function(e) {
            try {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, { type: 'array' });
                const firstSheetName = workbook.SheetNames[0];
                const worksheet = workbook.Sheets[firstSheetName];
                const htmlString = XLSX.utils.sheet_to_html(worksheet, { id: 'renderedExcelTable', editable: false });
                
                const grid = document.getElementById('excelGridContainer');
                if (grid) {
                    grid.innerHTML = htmlString;
                    const newTable = grid.querySelector('table');
                    if (newTable) {
                        newTable.className = 'excel-table';
                    }
                }
            } catch (err) {
                alert('Gagal membaca file Excel: ' + err.message);
            }
        };
        reader.readAsArrayBuffer(file);
    }
</script>
@endpush
@endsection

@extends('layouts.app')

@section('title', 'Kelola Kriteria & Bobot SPK - Admin Panel')

@section('content')
<div class="container-wide" style="padding-top: 36px; padding-bottom: 64px;">
    <!-- Admin Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 28px;">
        <div>
            <span class="badge badge-accent" style="margin-bottom: 6px;">Parameter SPK MARCOS</span>
            <h1 class="headline-lg">Kriteria & Bobot Keputusan</h1>
            <p class="body-sm">Kelola 7 kriteria proposal evaluasi make-up dan dekorasi pelaminan.</p>
        </div>
        <div style="display: flex; align-items: center; gap: 10px;">
            <span class="badge {{ round($totalWeight, 2) == 1.00 ? 'badge-success' : 'badge-amber' }}" style="font-size: 13px; padding: 6px 14px;">
                Total Bobot: {{ $totalWeight * 100 }}% ({{ number_format($totalWeight, 4) }})
            </span>
        </div>
    </div>

    <!-- Admin Navigation Chips -->
    <div style="display: flex; gap: 10px; margin-bottom: 32px; border-bottom: 1px solid var(--color-border); padding-bottom: 12px; overflow-x: auto;">
        <a href="{{ route('admin.dashboard') }}" class="chip">Ringkasan</a>
        <a href="{{ route('admin.criterias') }}" class="chip active">Kriteria & Bobot (C1-C7)</a>
        <a href="{{ route('admin.evaluations') }}" class="chip">Matriks Evaluasi Alternatif</a>
        <a href="{{ route('admin.orders') }}" class="chip">Pesanan & Pembayaran</a>
        <a href="{{ route('admin.spk') }}" class="chip">Laporan Lengkap MARCOS</a>
        <a href="{{ route('admin.users') }}" class="chip">Pengguna & Vendor</a>
    </div>

    <!-- Criterias List -->
    <div class="card-dribbble" style="padding: 24px; border-radius: 14px; margin-bottom: 36px;">
        <div class="table-responsive">
            <table class="table-dribbble">
                <thead>
                    <tr>
                        <th style="width: 80px;">Kode</th>
                        <th>Nama Kriteria</th>
                        <th>Kategori</th>
                        <th>Bobot Desimal</th>
                        <th>Persentase</th>
                        <th>Deskripsi Kriteria</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($criterias as $c)
                        <tr>
                            <td><strong>{{ $c->code }}</strong></td>
                            <td><strong>{{ $c->name }}</strong></td>
                            <td>
                                <span class="badge {{ $c->type === 'benefit' ? 'badge-success' : 'badge-amber' }}">
                                    {{ strtoupper($c->type) }}
                                </span>
                            </td>
                            <td><code>{{ number_format($c->weight, 4) }}</code></td>
                            <td><strong>{{ $c->weight * 100 }}%</strong></td>
                            <td class="caption" style="max-width: 320px;">{{ $c->description }}</td>
                            <td style="text-align: right;">
                                <button type="button" class="btn btn-secondary btn-sm" onclick="openEditCriteriaModal('{{ $c->id }}', '{{ $c->name }}', '{{ $c->type }}', '{{ $c->weight }}', '{{ addslashes($c->description) }}')">
                                    <i data-lucide="edit-3" style="width: 14px; height: 14px;"></i>
                                    Ubah
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Edit Kriteria (Clean Dribbble Modal) -->
<div id="criteriaModal" style="display: none; position: fixed; inset: 0; background: rgba(13,12,34,0.4); z-index: 1000; align-items: center; justify-content: center; padding: 20px;">
    <div class="card-dribbble" style="max-width: 520px; width: 100%; border-radius: 16px; padding: 32px; background: white; box-shadow: var(--shadow-hover);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 class="headline-sm" id="modalCriteriaTitle">Ubah Parameter Kriteria</h2>
            <button type="button" class="btn btn-tertiary" onclick="closeEditCriteriaModal()">✕</button>
        </div>

        <form id="criteriaForm" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label class="form-label" for="crit_name">Nama Kriteria</label>
                <input type="text" name="name" id="crit_name" class="input-pill" required>
            </div>

            <div class="grid-cols-2" style="gap: 16px;">
                <div class="form-group">
                    <label class="form-label" for="crit_type">Kategori Sifat</label>
                    <select name="type" id="crit_type" class="select-pill" required>
                        <option value="benefit">Benefit (Maksimal)</option>
                        <option value="cost">Cost (Minimal)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="crit_weight">Bobot (0.01 - 1.00)</label>
                    <input type="number" name="weight" id="crit_weight" class="input-pill" step="0.01" min="0.01" max="1" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="crit_desc">Deskripsi & Indikator</label>
                <textarea name="description" id="crit_desc" class="textarea-pill" rows="3"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px;">
                <button type="button" class="btn btn-secondary" onclick="closeEditCriteriaModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openEditCriteriaModal(id, name, type, weight, desc) {
        document.getElementById('criteriaForm').action = '/admin/kriteria/' + id;
        document.getElementById('crit_name').value = name;
        document.getElementById('crit_type').value = type;
        document.getElementById('crit_weight').value = weight;
        document.getElementById('crit_desc').value = desc;
        document.getElementById('criteriaModal').style.display = 'flex';
    }

    function closeEditCriteriaModal() {
        document.getElementById('criteriaModal').style.display = 'none';
    }
</script>
@endpush
@endsection

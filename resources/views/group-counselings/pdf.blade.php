<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Konseling Kelompok</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; color: #000; background: #fff; }

        /* Kop Surat */
        .kop-surat { border-bottom: 3px solid #000; padding-bottom: 8px; margin-bottom: 16px; }
        .kop-inner { display: flex; align-items: center; gap: 16px; }
        .kop-logo { width: 80px; height: 80px; border: 1px solid #ccc; display: flex; align-items: center; justify-content: center; font-size: 9pt; color: #888; flex-shrink: 0; }
        .kop-text { flex: 1; text-align: center; }
        .kop-text .sekolah-nama { font-size: 16pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .kop-text .sekolah-info { font-size: 10pt; margin-top: 2px; }

        /* Judul Dokumen */
        .judul-dokumen { text-align: center; margin: 20px 0 16px; }
        .judul-dokumen h2 { font-size: 14pt; font-weight: bold; text-transform: uppercase; text-decoration: underline; letter-spacing: 1px; }

        /* Nomor & info */
        .info-list { margin-bottom: 16px; }
        .info-list table { width: 100%; }
        .info-list td { padding: 2px 4px; font-size: 11pt; vertical-align: top; }
        .info-list td:first-child { width: 180px; }
        .info-list td:nth-child(2) { width: 16px; }

        /* Section title */
        .section-title { font-weight: bold; font-size: 11pt; margin: 14px 0 6px; text-decoration: underline; }

        /* Content box */
        .content-box { border: 1px solid #000; padding: 8px 10px; min-height: 40px; font-size: 11pt; margin-bottom: 10px; white-space: pre-wrap; }

        /* Peserta Table */
        .peserta-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .peserta-table th, .peserta-table td { border: 1px solid #000; padding: 6px 8px; font-size: 10pt; }
        .peserta-table th { background: #f0f0f0; font-weight: bold; text-align: center; }
        .peserta-table td:first-child, .peserta-table td:nth-child(3) { text-align: center; }

        /* Tanda tangan */
        .ttd-section { margin-top: 40px; }
        .ttd-row { display: flex; justify-content: space-between; }
        .ttd-box { width: 45%; text-align: center; }
        .ttd-box .ttd-label { font-size: 11pt; font-weight: bold; margin-bottom: 4px; }
        .ttd-box .ttd-space { height: 70px; }
        .ttd-box .ttd-nama { font-size: 11pt; font-weight: bold; border-top: 1px solid #000; padding-top: 4px; display: inline-block; min-width: 200px; }
        .ttd-box .ttd-nip { font-size: 10pt; }

        .page-break { page-break-after: always; }
        @page { margin: 2cm 2.5cm; }
    </style>
</head>
<body>

{{-- Kop Surat --}}
<div class="kop-surat">
    <div class="kop-inner">
        <div class="kop-logo">LOGO</div>
        <div class="kop-text">
            <div class="sekolah-nama">{{ $school['nama'] }}</div>
            <div class="sekolah-info">{{ $school['alamat'] }}</div>
            <div class="sekolah-info">
                Telp: {{ $school['telepon'] }}
                @if($school['email']) &nbsp;|&nbsp; Email: {{ $school['email'] }} @endif
            </div>
        </div>
    </div>
</div>

{{-- Judul --}}
<div class="judul-dokumen">
    <h2>Laporan Konseling Kelompok</h2>
</div>

{{-- Informasi Kegiatan --}}
<div class="info-list">
    <table>
        <tr>
            <td>Topik / Tema</td>
            <td>:</td>
            <td>{{ $counseling->topic }}</td>
        </tr>
        <tr>
            <td>Tahun Ajaran</td>
            <td>:</td>
            <td>{{ $counseling->academicYear->name }}</td>
        </tr>
        <tr>
            <td>Tanggal Pelaksanaan</td>
            <td>:</td>
            <td>{{ $counseling->scheduled_at ? $counseling->scheduled_at->translatedFormat('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td>Waktu</td>
            <td>:</td>
            <td>{{ $counseling->scheduled_at ? $counseling->scheduled_at->format('H:i') . ' WIB' : '-' }}</td>
        </tr>
        <tr>
            <td>Konselor / Guru BK</td>
            <td>:</td>
            <td>{{ $counseling->counselor->name }}</td>
        </tr>
        <tr>
            <td>Jumlah Peserta</td>
            <td>:</td>
            <td>{{ $counseling->participants->count() }} Siswa</td>
        </tr>
        <tr>
            <td>Status</td>
            <td>:</td>
            <td>
                @php
                    $statusLabels = ['scheduled' => 'Dijadwalkan', 'ongoing' => 'Berlangsung', 'completed' => 'Selesai'];
                @endphp
                {{ $statusLabels[$counseling->status] ?? $counseling->status }}
            </td>
        </tr>
    </table>
</div>

{{-- Deskripsi --}}
@if($counseling->description)
<div class="section-title">Deskripsi / Tujuan Kegiatan</div>
<div class="content-box">{{ $counseling->description }}</div>
@endif

{{-- Metode --}}
@if($counseling->method)
<div class="section-title">Metode / Teknik Konseling</div>
<div class="content-box">{{ $counseling->method }}</div>
@endif

{{-- Hasil --}}
<div class="section-title">Hasil Kegiatan</div>
<div class="content-box">{{ $counseling->result ?: '-' }}</div>

{{-- Evaluasi --}}
<div class="section-title">Evaluasi</div>
<div class="content-box">{{ $counseling->evaluation ?: '-' }}</div>

{{-- Tabel Peserta --}}
<div class="section-title">Daftar Peserta</div>
<table class="peserta-table">
    <thead>
        <tr>
            <th style="width:40px">No</th>
            <th>Nama Siswa</th>
            <th style="width:110px">NIS</th>
            <th>Catatan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($counseling->participants as $i => $participant)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $participant->full_name }}</td>
            <td>{{ $participant->nis }}</td>
            <td>{{ $participant->pivot->notes ?: '-' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="4" style="text-align:center">Belum ada peserta</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{-- Tanda Tangan --}}
<div class="ttd-section">
    <div class="ttd-row">
        {{-- Kepala Sekolah --}}
        <div class="ttd-box">
            <div class="ttd-label">Mengetahui,</div>
            <div class="ttd-label">Kepala Sekolah</div>
            <div class="ttd-space"></div>
            <div>
                <span class="ttd-nama">{{ $school['kepala_sekolah']['nama'] }}</span>
            </div>
            <div class="ttd-nip">NIP. {{ $school['kepala_sekolah']['nip'] }}</div>
        </div>

        {{-- Guru BK --}}
        <div class="ttd-box">
            @php
                $tanggal = $counseling->scheduled_at ? $counseling->scheduled_at->translatedFormat('d F Y') : now()->translatedFormat('d F Y');
            @endphp
            <div class="ttd-label">{{ $counseling->academicYear->name }},</div>
            <div class="ttd-label">Guru Bimbingan Konseling</div>
            <div class="ttd-space"></div>
            <div>
                <span class="ttd-nama">{{ $counseling->counselor->name }}</span>
            </div>
            @php
                $counselorTeacher = \App\Models\Teacher::query()->where('user_id', $counseling->counselor->id)->first();
            @endphp
            <div class="ttd-nip">NIP. {{ $counselorTeacher?->nip ?? '-' }}</div>
        </div>
    </div>
</div>

</body>
</html>

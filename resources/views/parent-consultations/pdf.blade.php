<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Konsultasi Orang Tua</title>
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

        /* Info list */
        .info-list { margin-bottom: 16px; }
        .info-list table { width: 100%; }
        .info-list td { padding: 2px 4px; font-size: 11pt; vertical-align: top; }
        .info-list td:first-child { width: 200px; }
        .info-list td:nth-child(2) { width: 16px; }

        /* Section title */
        .section-title { font-weight: bold; font-size: 11pt; margin: 14px 0 6px; text-decoration: underline; }

        /* Content box */
        .content-box { border: 1px solid #000; padding: 8px 10px; min-height: 40px; font-size: 11pt; margin-bottom: 10px; white-space: pre-wrap; }

        /* Tanda tangan – 3 kolom */
        .ttd-section { margin-top: 40px; }
        .ttd-row { display: flex; justify-content: space-between; }
        .ttd-box { width: 45%; text-align: center; }
        .ttd-box .ttd-label { font-size: 11pt; font-weight: bold; margin-bottom: 4px; }
        .ttd-box .ttd-space { height: 70px; }
        .ttd-box .ttd-nama { font-size: 11pt; font-weight: bold; border-top: 1px solid #000; padding-top: 4px; display: inline-block; min-width: 200px; }
        .ttd-box .ttd-nip { font-size: 10pt; }

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
    <h2>Laporan Konsultasi Orang Tua</h2>
</div>

{{-- Informasi --}}
<div class="info-list">
    <table>
        <tr>
            <td>Nama Orang Tua / Wali</td>
            <td>:</td>
            <td>{{ $consultation->guardian->full_name }}</td>
        </tr>
        <tr>
            <td>Nama Siswa</td>
            <td>:</td>
            <td>{{ $consultation->student->full_name }}</td>
        </tr>
        <tr>
            <td>NIS Siswa</td>
            <td>:</td>
            <td>{{ $consultation->student->nis }}</td>
        </tr>
        <tr>
            <td>Tahun Ajaran</td>
            <td>:</td>
            <td>{{ $consultation->academicYear->name }}</td>
        </tr>
        <tr>
            <td>Tanggal Konsultasi</td>
            <td>:</td>
            <td>{{ $consultation->scheduled_at ? $consultation->scheduled_at->translatedFormat('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td>Waktu</td>
            <td>:</td>
            <td>{{ $consultation->scheduled_at ? $consultation->scheduled_at->format('H:i') . ' WIB' : '-' }}</td>
        </tr>
        <tr>
            <td>Pemohon</td>
            <td>:</td>
            <td>{{ $consultation->requested_by === 'guru_bk' ? 'Guru Bimbingan Konseling' : 'Orang Tua / Wali' }}</td>
        </tr>
        <tr>
            <td>Guru BK</td>
            <td>:</td>
            <td>{{ $consultation->counselor->name }}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td>:</td>
            <td>
                @php
                    $statusLabels = ['requested' => 'Diminta', 'scheduled' => 'Dijadwalkan', 'completed' => 'Selesai'];
                @endphp
                {{ $statusLabels[$consultation->status] ?? $consultation->status }}
            </td>
        </tr>
    </table>
</div>

{{-- Topik --}}
<div class="section-title">Topik Pembahasan</div>
<div class="content-box">{{ $consultation->topic ?: '-' }}</div>

{{-- Hasil --}}
<div class="section-title">Hasil Konsultasi</div>
<div class="content-box">{{ $consultation->result ?: '-' }}</div>

{{-- Kesepakatan --}}
<div class="section-title">Kesepakatan / Tindak Lanjut</div>
<div class="content-box">{{ $consultation->agreement ?: '-' }}</div>

{{-- Tanda Tangan --}}
<div class="ttd-section">
    <div class="ttd-row">
        {{-- Orang Tua --}}
        <div class="ttd-box">
            <div class="ttd-label">Orang Tua / Wali,</div>
            <div class="ttd-space"></div>
            <div>
                <span class="ttd-nama">{{ $consultation->guardian->full_name }}</span>
            </div>
        </div>

        {{-- Guru BK --}}
        <div class="ttd-box">
            <div class="ttd-label">Guru Bimbingan Konseling,</div>
            <div class="ttd-space"></div>
            <div>
                <span class="ttd-nama">{{ $consultation->counselor->name }}</span>
            </div>
            @php
                $counselorTeacher = \App\Models\Teacher::query()->where('user_id', $consultation->counselor->id)->first();
            @endphp
            <div class="ttd-nip">NIP. {{ $counselorTeacher?->nip ?? '-' }}</div>
        </div>
    </div>
</div>

</body>
</html>

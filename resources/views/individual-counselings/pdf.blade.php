<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Konseling Individual - {{ $counseling->student->full_name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #000; background: #fff; }
        .container { max-width: 800px; margin: 0 auto; padding: 30px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 12px; margin-bottom: 20px; }
        .header h2 { font-size: 16px; font-weight: bold; text-transform: uppercase; margin-bottom: 4px; }
        .header p { font-size: 11px; }
        .section-title { font-weight: bold; font-size: 12px; background: #f0f0f0; padding: 5px 8px; margin: 14px 0 6px 0; border-left: 3px solid #333; }
        table.info { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table.info td { padding: 4px 8px; font-size: 11px; vertical-align: top; }
        table.info td:first-child { width: 35%; font-weight: bold; }
        .field-box { border: 1px solid #ccc; padding: 8px; min-height: 50px; font-size: 11px; margin-bottom: 10px; white-space: pre-wrap; }
        .field-label { font-weight: bold; font-size: 11px; margin-bottom: 3px; }
        .photos { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 8px; }
        .photos img { width: 120px; height: 90px; object-fit: cover; border: 1px solid #ccc; }
        @media print {
            body { -webkit-print-color-adjust: exact; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
<div class="container">
    {{-- Header --}}
    <div class="header">
        <h2>Laporan Bimbingan Konseling Individual</h2>
        <p>Tahun Ajaran: {{ $counseling->academicYear->name }}</p>
    </div>

    {{-- Info Siswa --}}
    <div class="section-title">Data Siswa</div>
    <table class="info">
        <tr><td>Nama Siswa</td><td>: {{ $counseling->student->full_name }}</td></tr>
        <tr><td>NIS</td><td>: {{ $counseling->student->nis }}</td></tr>
        <tr><td>Tanggal Sesi</td><td>: {{ $counseling->scheduled_at->format('d F Y, H:i') }}</td></tr>
        <tr><td>Kategori</td><td>: {{ ucfirst($counseling->category) }}</td></tr>
        <tr><td>Status</td><td>: {{ ['scheduled'=>'Dijadwalkan','ongoing'=>'Berlangsung','completed'=>'Selesai','followed_up'=>'Tindak Lanjut'][$counseling->status] ?? $counseling->status }}</td></tr>
        <tr><td>Konselor BK</td><td>: {{ $counseling->counselor->name }}</td></tr>
    </table>

    {{-- Deskripsi Masalah --}}
    <div class="section-title">Deskripsi Masalah</div>
    <div class="field-box">{{ $counseling->problem_description ?: '-' }}</div>

    {{-- Pendekatan --}}
    <div class="section-title">Pendekatan / Teknik Konseling</div>
    <div class="field-box">{{ $counseling->approach ?: '-' }}</div>

    {{-- Hasil --}}
    <div class="section-title">Hasil Konseling</div>
    <div class="field-box">{{ $counseling->result ?: '-' }}</div>

    {{-- Evaluasi --}}
    <div class="section-title">Evaluasi</div>
    <div class="field-box">{{ $counseling->evaluation ?: '-' }}</div>

    {{-- Tindak Lanjut --}}
    <div class="section-title">Tindak Lanjut</div>
    <div class="field-box">{{ $counseling->follow_up ?: '-' }}</div>

    {{-- Dokumentasi Foto --}}
    @if($counseling->documents->count() > 0)
        <div class="section-title">Dokumentasi Foto</div>
        <div class="photos">
            @foreach($counseling->documents as $doc)
                <img src="{{ public_path('storage/' . $doc->file_path) }}" alt="{{ $doc->file_name }}">
            @endforeach
        </div>
    @endif

    {{-- Tanda Tangan --}}
    <div style="margin-top: 30px;">
        <p style="font-size:11px; margin-bottom:20px;">
            Dibuat pada: {{ now()->format('d F Y') }}
        </p>
        <table style="width:100%; border-collapse:collapse;">
            <tr>
                <td style="width:33%; text-align:center; padding:0 10px; vertical-align:top;">
                    <p style="margin:0 0 4px 0; font-size:11px;">Guru Bimbingan dan Konseling,</p>
                    <div style="height:60px;"></div>
                    <p style="margin:0; font-size:11px; font-weight:bold; border-top:1px solid #000; padding-top:4px;">{{ $counseling->counselor->name }}</p>
                    @php $counselorTeacher = $counseling->counselor->teacher; @endphp
                    <p style="margin:2px 0 0 0; font-size:10px;">NIP. {{ $counselorTeacher?->nip ?: '-' }}</p>
                </td>
                <td style="width:33%; text-align:center; padding:0 10px; vertical-align:top;">
                    <p style="margin:0 0 4px 0; font-size:11px;">Mengetahui,<br>Wali Kelas</p>
                    <div style="height:60px;"></div>
                    <p style="margin:0; font-size:11px; font-weight:bold; border-top:1px solid #000; padding-top:4px;">{{ $homeroomTeacher?->full_name ?: '................................' }}</p>
                    <p style="margin:2px 0 0 0; font-size:10px;">NIP. {{ $homeroomTeacher?->nip ?: '-' }}</p>
                </td>
                <td style="width:33%; text-align:center; padding:0 10px; vertical-align:top;">
                    <p style="margin:0 0 4px 0; font-size:11px;">Mengetahui,<br>Kepala Sekolah</p>
                    <div style="height:60px;"></div>
                    <p style="margin:0; font-size:11px; font-weight:bold; border-top:1px solid #000; padding-top:4px;">{{ $principalName ?: '................................' }}</p>
                    <p style="margin:2px 0 0 0; font-size:10px;">NIP. {{ $principalNip ?: '-' }}</p>
                </td>
            </tr>
        </table>
    </div>

    {{-- Print Button --}}
    <div class="no-print" style="margin-top:20px; text-align:center;">
        <button onclick="window.print()" style="padding:8px 24px; background:#2563eb; color:#fff; border:none; border-radius:6px; cursor:pointer; font-size:13px;">
            🖨️ Cetak / Simpan PDF
        </button>
        <button onclick="window.close()" style="padding:8px 24px; background:#6b7280; color:#fff; border:none; border-radius:6px; cursor:pointer; font-size:13px; margin-left:8px;">
            Tutup
        </button>
    </div>
</div>
</body>
</html>

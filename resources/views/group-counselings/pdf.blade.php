<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Bimbingan - {{ $counseling->topic }}</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:Arial,sans-serif; font-size:12px; color:#000; background:#fff; }
        .container { max-width:800px; margin:0 auto; padding:30px; }
        .header { text-align:center; border-bottom:2px solid #000; padding-bottom:12px; margin-bottom:20px; }
        .header h2 { font-size:16px; font-weight:bold; text-transform:uppercase; margin-bottom:4px; }
        .section-title { font-weight:bold; font-size:12px; background:#f0f0f0; padding:5px 8px; margin:14px 0 6px 0; border-left:3px solid #333; }
        table.info { width:100%; border-collapse:collapse; margin-bottom:10px; }
        table.info td { padding:4px 8px; font-size:11px; vertical-align:top; }
        table.info td:first-child { width:35%; font-weight:bold; }
        .field-box { border:1px solid #ccc; padding:8px; min-height:50px; font-size:11px; margin-bottom:10px; white-space:pre-wrap; }
        table.participants { width:100%; border-collapse:collapse; font-size:11px; }
        table.participants th { background:#f0f0f0; padding:5px 8px; text-align:left; border:1px solid #ccc; }
        table.participants td { padding:4px 8px; border:1px solid #ccc; }
        @media print { .no-print { display:none; } }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>Laporan {{ $counseling->service_type?->label() ?? 'Bimbingan' }}</h2>
        <p>Tahun Ajaran: {{ $counseling->academicYear->name }}</p>
    </div>

    <div class="section-title">Informasi Kegiatan</div>
    <table class="info">
        <tr><td>Topik</td><td>: {{ $counseling->topic }}</td></tr>
        <tr><td>Jenis Layanan</td><td>: {{ $counseling->service_type?->label() ?? '-' }}</td></tr>
        <tr><td>Tanggal</td><td>: {{ $counseling->scheduled_at?->format('d F Y, H:i') ?? '-' }}</td></tr>
        <tr><td>Jumlah Peserta</td><td>: {{ $counseling->participants->count() }} siswa</td></tr>
        <tr><td>Konselor BK</td><td>: {{ $counseling->counselor->name }}</td></tr>
    </table>

    <div class="section-title">Deskripsi / Tujuan</div>
    <div class="field-box">{{ $counseling->description ?: '-' }}</div>

    <div class="section-title">Metode / Teknik</div>
    <div class="field-box">{{ $counseling->method ?: '-' }}</div>

    <div class="section-title">Hasil Kegiatan</div>
    <div class="field-box">{{ $counseling->result ?: '-' }}</div>

    <div class="section-title">Evaluasi</div>
    <div class="field-box">{{ $counseling->evaluation ?: '-' }}</div>

    <div class="section-title">Tindak Lanjut</div>
    <div class="field-box">{{ $counseling->follow_up ?: '-' }}</div>

    <div class="section-title">Daftar Peserta</div>
    <table class="participants">
        <thead>
            <tr><th>No</th><th>Nama Siswa</th><th>NIS</th><th>Catatan</th></tr>
        </thead>
        <tbody>
            @forelse($counseling->participants as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $p->full_name }}</td>
                    <td>{{ $p->nis }}</td>
                    <td>{{ $p->pivot->notes ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" style="text-align:center;">Tidak ada peserta</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Tanda Tangan --}}
    <div style="margin-top:30px;">
        <p style="font-size:11px; margin-bottom:20px;">Dibuat pada: {{ now()->format('d F Y') }}</p>
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
                    <p style="margin:0; font-size:11px; font-weight:bold; border-top:1px solid #000; padding-top:4px;">................................</p>
                    <p style="margin:2px 0 0 0; font-size:10px;">NIP. -</p>
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

    <div class="no-print" style="margin-top:20px; text-align:center;">
        <button onclick="window.print()" style="padding:8px 24px; background:#2563eb; color:#fff; border:none; border-radius:6px; cursor:pointer; font-size:13px;">🖨️ Cetak / Simpan PDF</button>
        <button onclick="window.close()" style="padding:8px 24px; background:#6b7280; color:#fff; border:none; border-radius:6px; cursor:pointer; font-size:13px; margin-left:8px;">Tutup</button>
    </div>
</div>
</body>
</html>

{{-- Reusable: 3-kolom tanda tangan PDF --}}
{{-- Params: $counselorName, $counselorNip, $homeroomTeacherName, $homeroomTeacherNip, $principalName, $principalNip, $city, $date --}}
<div style="margin-top:40px;">
    <table style="width:100%; border-collapse:collapse;">
        <tr>
            <td style="width:33%; text-align:center; padding:0 10px; vertical-align:top;">
                <p style="margin:0 0 4px 0; font-size:11px;">Guru Bimbingan dan Konseling,</p>
                <div style="height:60px;"></div>
                <p style="margin:0; font-size:11px; font-weight:bold; border-top:1px solid #000; padding-top:4px;">
                    {{ $counselorName }}
                </p>
                <p style="margin:2px 0 0 0; font-size:10px;">NIP. {{ $counselorNip ?: '-' }}</p>
            </td>
            <td style="width:33%; text-align:center; padding:0 10px; vertical-align:top;">
                <p style="margin:0 0 4px 0; font-size:11px;">Mengetahui,<br>Wali Kelas</p>
                <div style="height:60px;"></div>
                <p style="margin:0; font-size:11px; font-weight:bold; border-top:1px solid #000; padding-top:4px;">
                    {{ $homeroomTeacherName ?: '................................' }}
                </p>
                <p style="margin:2px 0 0 0; font-size:10px;">NIP. {{ $homeroomTeacherNip ?: '-' }}</p>
            </td>
            <td style="width:33%; text-align:center; padding:0 10px; vertical-align:top;">
                <p style="margin:0 0 4px 0; font-size:11px;">Mengetahui,<br>Kepala Sekolah</p>
                <div style="height:60px;"></div>
                <p style="margin:0; font-size:11px; font-weight:bold; border-top:1px solid #000; padding-top:4px;">
                    {{ $principalName ?: '................................' }}
                </p>
                <p style="margin:2px 0 0 0; font-size:10px;">NIP. {{ $principalNip ?: '-' }}</p>
            </td>
        </tr>
    </table>
</div>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kontrak Kerja - {{ $kontrak->nomor_kontrak }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 14px; color: #000; line-height: 1.6; text-align: justify; padding: 20px; }
        h1, h2, h3 { text-align: center; margin: 5px 0; }
        .header { margin-bottom: 30px; border-bottom: 3px double #000; padding-bottom: 10px; }
        .title { font-size: 18px; font-weight: bold; text-transform: uppercase; text-decoration: underline; }
        .subtitle { font-size: 14px; text-align: center; margin-bottom: 30px; }
        .section { margin-top: 20px; }
        .section-title { font-weight: bold; margin-bottom: 10px; }
        .indent { margin-left: 30px; }
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; padding: 3px; }
        .td-label { width: 150px; }
        .td-colon { width: 20px; text-align: center; }
        .signature-area { margin-top: 50px; width: 100%; page-break-inside: avoid; }
        .signature-box { height: 100px; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

    <div class="header text-center">
        <h1>SURAT PERJANJIAN KONTRAK KERJA PEMBANGUNAN</h1>
        <p>Nomor Kontrak: {{ $kontrak->nomor_kontrak }}</p>
    </div>

    <p>Pada hari ini, <strong>{{ \Carbon\Carbon::parse($kontrak->created_at)->translatedFormat('l, d F Y') }}</strong>, kami yang bertanda tangan di bawah ini:</p>

    <div class="indent">
        <table>
            <tr>
                <td class="td-label">Nama</td>
                <td class="td-colon">:</td>
                <td><strong>{{ $kontrak->konsumen->name }}</strong></td>
            </tr>
            <tr>
                <td class="td-label">Alamat</td>
                <td class="td-colon">:</td>
                <td>{{ $kontrak->konsumen->profile->alamat ?? '-' }}</td>
            </tr>
            <tr>
                <td class="td-label">No. Telp/WA</td>
                <td class="td-colon">:</td>
                <td>{{ $kontrak->konsumen->profile->no_hp ?? '-' }}</td>
            </tr>
        </table>
    </div>
    <p>Dalam hal ini bertindak untuk dan atas nama diri sendiri, yang selanjutnya dalam Surat Perjanjian ini disebut sebagai <strong>PIHAK PERTAMA (PEMILIK PROYEK)</strong>.</p>

    <div class="indent mt-3">
        <table>
            <tr>
                <td class="td-label">Nama</td>
                <td class="td-colon">:</td>
                <td><strong>{{ $kontrak->tukang->name }}</strong></td>
            </tr>
            <tr>
                <td class="td-label">Alamat</td>
                <td class="td-colon">:</td>
                <td>{{ $kontrak->tukang->profile->alamat ?? '-' }}</td>
            </tr>
            <tr>
                <td class="td-label">No. Telp/WA</td>
                <td class="td-colon">:</td>
                <td>{{ $kontrak->tukang->profile->no_hp ?? '-' }}</td>
            </tr>
        </table>
    </div>
    <p>Dalam hal ini bertindak sebagai perencana dan pelaksana pembangunan, yang selanjutnya disebut sebagai <strong>PIHAK KEDUA (KEPALA TUKANG)</strong>.</p>

    <p>Kedua belah pihak telah sepakat untuk mengikatkan diri dalam Perjanjian Kontrak Kerja Pembangunan Rumah dengan syarat dan ketentuan sebagai berikut:</p>

    <div class="section">
        <div class="section-title">PASAL 1 : LINGKUP PEKERJAAN</div>
        <p>PIHAK PERTAMA memberikan tugas kepada PIHAK KEDUA dan PIHAK KEDUA menerima tugas tersebut untuk melaksanakan pembangunan <strong>{{ $kontrak->permintaan->tipeRumah->nama_tipe }}</strong> dengan luas bangunan <strong>{{ number_format($kontrak->permintaan->luas_bangunan, 2, ',', '.') }} m²</strong> yang berlokasi di <strong>{{ $kontrak->permintaan->lokasi_proyek }}</strong>. Spesifikasi pekerjaan sesuai dengan Rencana Anggaran Biaya (RAB) Nomor: <strong>{{ $kontrak->rab->nomor_rab }}</strong> yang menjadi lampiran tak terpisahkan dari perjanjian ini.</p>
    </div>

    <div class="section">
        <div class="section-title">PASAL 2 : NILAI KONTRAK</div>
        <p>Nilai keseluruhan untuk pembangunan rumah sebagaimana dimaksud pada Pasal 1 adalah sebesar <strong>Rp {{ number_format($kontrak->nilai_kontrak, 0, ',', '.') }}</strong> (<em>Terbilang: {{ ucwords((new \NumberFormatter('id', \NumberFormatter::SPELLOUT))->format($kontrak->nilai_kontrak)) }} Rupiah</em>). Nilai ini mencakup seluruh biaya material, upah pekerja, jasa PIHAK KEDUA, dan biaya terkait lainnya sesuai yang disepakati di dalam RAB.</p>
    </div>

    <div class="section">
        <div class="section-title">PASAL 3 : JANGKA WAKTU PELAKSANAAN</div>
        <p>Pekerjaan pembangunan akan dimulai pada <strong>{{ \Carbon\Carbon::parse($kontrak->tanggal_mulai)->translatedFormat('d F Y') }}</strong> dan ditargetkan selesai selambat-lambatnya pada <strong>{{ \Carbon\Carbon::parse($kontrak->tanggal_selesai)->translatedFormat('d F Y') }}</strong>. Perubahan jadwal karena faktor tak terduga (force majeure) akan disepakati bersama oleh kedua belah pihak.</p>
    </div>

    <div class="section">
        <div class="section-title">PASAL 4 : PENYELESAIAN PERSELISIHAN</div>
        <p>Apabila terjadi perselisihan antara kedua belah pihak terkait pelaksanaan perjanjian ini, maka akan diselesaikan secara musyawarah untuk mufakat. Apabila tidak tercapai mufakat, maka akan diselesaikan sesuai dengan ketentuan hukum yang berlaku.</p>
    </div>

    <p style="margin-top: 30px;">Demikian Surat Perjanjian Kontrak Kerja ini dibuat dan ditandatangani oleh kedua belah pihak dalam keadaan sadar dan tanpa paksaan dari pihak manapun.</p>

    <table class="signature-area">
        <tr>
            <td width="50%" class="text-center">
                <p><strong>PIHAK PERTAMA</strong><br>Pemilik Proyek</p>
                <div class="signature-box"></div>
                <p><u><strong>{{ $kontrak->konsumen->name }}</strong></u></p>
            </td>
            <td width="50%" class="text-center">
                <p><strong>PIHAK KEDUA</strong><br>Kepala Tukang</p>
                <div class="signature-box"></div>
                <p><u><strong>{{ $kontrak->tukang->name }}</strong></u></p>
            </td>
        </tr>
    </table>

</body>
</html>

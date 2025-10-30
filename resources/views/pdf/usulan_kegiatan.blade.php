<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Usulan Kegiatan</title>
    <style>
        @page {
            margin: 50px 40px;
        }
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 12pt;
            line-height: 1.5;
        }
        h2 {
            text-align: center;
            margin-top: 6px;
        }
        p {
            margin: 4px 0;
        }
        .content {
            margin-top: 15px;
        }
        .right {
            text-align: right;
            margin-top: 60px;
        }
        .kop {
            width: 100%;
            height: auto;
            margin-bottom: 10px;
        }
        .ttd {
            height: 80px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    {{-- Kop surat (jika ada) --}}
    @if($usulankegiatan->kop_surat)
        <img src="{{ $usulankegiatan->kop_surat }}" class="kop">
    @endif

    <h2>Surat Usulan Kegiatan</h2>

    <div class="content">
        <p><strong>Nama Kegiatan:</strong> {{ $usulankegiatan->nama_kegiatan }}</p>
        <p><strong>Lokasi:</strong> {{ $usulankegiatan->lokasi_kegiatan ?? '-' }}</p>
        <p><strong>Tanggal:</strong>
            {{ $usulankegiatan->tanggal_pelaksanaan 
                ? \Carbon\Carbon::parse($usulankegiatan->tanggal_pelaksanaan)->translatedFormat('d F Y') 
                : '-' }}
        </p>
        <p><strong>Subunit Kerja:</strong> {{ optional($usulankegiatan->subunitkerja)->sub_unitkerja ?? '-' }}</p>
        <p><strong>Dibuat Oleh:</strong> 
            {{ optional($usulankegiatan->creator)->nama ?? optional($usulankegiatan->creator)->name ?? '-' }}
        </p>
    </div>

    <div class="right">
        <p>Hormat kami,</p>
        <br><br>
        @if($usulankegiatan->tanda_tangan)
            <img src="{{ $usulankegiatan->tanda_tangan }}" class="ttd">
        @endif
    </div>

</body>
</html>

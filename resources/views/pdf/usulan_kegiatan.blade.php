<!doctype html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .kop {
            width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        .ttd {
            width: 150px;
            height: auto;
        }

        .right {
            text-align: right;
        }
    </style>
</head>

<body>

    {{-- Kop surat (jika ada) --}}
    @if($usulankegiatan->kop_surat)
        <img src="{{ $usulankegiatan->kop_surat }}" style="width:100%; height:auto;" />
    @endif

    <h2 style="text-align:center; margin-top: 6px;">Surat Usulan Kegiatan</h2>

    <div style="margin-top: 12px;">
        <p><strong>Nama Kegiatan:</strong> {{ $usulankegiatan->nama_kegiatan }}</p>
        <p><strong>Lokasi:</strong> {{ $usulankegiatan->lokasi_kegiatan ?? '-' }}</p>
        <p><strong>Tanggal:</strong>
            {{ $usulankegiatan->tanggal_pelaksanaan ? \Carbon\Carbon::parse($usulankegiatan->tanggal_pelaksanaan)->translatedFormat('d F Y') : '-' }}
        </p>
        <p><strong>Subunit Kerja:</strong> {{ optional($usulankegiatan->subunitkerja)->sub_unitkerja ?? '-' }}</p>
        <p><strong>Dibuat Oleh:</strong>
            {{ optional($usulankegiatan->creator)->nama ?? optional($usulankegiatan->creator)->name ?? '-' }}</p>
    </div>

    <br><br>
    <div class="right">
        <p>Hormat kami,</p>
        <br><br>
        @if($usulankegiatan->tanda_tangan)
            <div style="text-align:right; margin-top:50px;">
                <img src="{{ $usulankegiatan->tanda_tangan }}" style="height:80px;" />
            </div>
        @endif
    </div>
</body>

</html>
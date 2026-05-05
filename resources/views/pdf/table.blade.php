<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
            padding: 30px;
        }

        .header {
            border-bottom: 3px solid #1f2937;
            padding-bottom: 12px;
            margin-bottom: 25px;
        }

        .header-table {
            width: 100%;
        }

        .header-left h1 {
            font-size: 32px;
            color: #111827;
            margin-bottom: 4px;
        }

        .header-left p {
            font-size: 11px;
            color: #555;
            line-height: 1.5;
        }

        .header-right {
            text-align: right;
            vertical-align: top;
        }

        .header-right .code {
            font-size: 11px;
            color: #666;
        }

        .report-title {
            margin-top: 20px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 1px;
            color: #111827;
        }

        .info-box {
            margin-top: 20px;
            border: 1px solid #cbd5e1;
            padding: 12px;
            background: #f8fafc;
        }

        .info-box table {
            width: 100%;
        }

        .info-box td {
            padding: 4px 0;
            font-size: 11px;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
        }

        .main-table th {
            background: #1f2937;
            color: white;
            font-size: 11px;
            padding: 10px;
            border: 1px solid #111827;
            text-align: center;
        }

        .main-table td {
            border: 1px solid #d1d5db;
            padding: 8px;
            font-size: 11px;
        }

        .main-table tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            position: fixed;
            bottom: 15px;
            left: 30px;
            right: 30px;
            border-top: 1px solid #999;
            padding-top: 6px;
            font-size: 10px;
            color: #555;
        }

        .footer-left {
            float: left;
        }

        .footer-right {
            float: right;
        }

        .clearfix::after {
            content: "";
            display: block;
            clear: both;
        }

        .signature {
            margin-top: 45px;
            width: 100%;
        }

        .signature td {
            text-align: right;
            font-size: 11px;
        }

        .signature-space {
            height: 55px;
        }

        .summary-box {
            margin-top: 18px;
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            padding: 12px;
        }

        .summary-title {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #111827;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 5px;
        }

        .summary-box table {
            width: 100%;
        }

        .summary-box td {
            padding: 4px 0;
            font-size: 11px;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="header-left">
                    <h1>JOKOPUS</h1>
                    <p>
                        Hasil cetak laporan Jokopus
                    </p>
                </td>
                <td class="header-right">
                    <div class="code">No: PDF/{{ strtoupper($type) }}/{{ date('Ymd') }}</div>
                </td>
            </tr>
        </table>

        <div class="report-title">
            LAPORAN {{ strtoupper($type) }}
        </div>
    </div>
    <div class="summary-box">
        <div class="summary-title">RINGKASAN</div>

        <table>
            @if($type == 'buku')
                <tr>
                    <td width="35%">Total Buku</td>
                    <td width="3%">:</td>
                    <td>{{ $data->count() }} Buku</td>
                </tr>
                <tr>
                    <td>Total Penulis</td>
                    <td>:</td>
                    <td>{{ $data->pluck('penulis.nama')->filter()->unique()->count() }} Penulis</td>
                </tr>
                <tr>
                    <td>Total Kategori</td>
                    <td>:</td>
                    <td>{{ $data->pluck('kategori.nama')->filter()->unique()->count() }} Kategori</td>
                </tr>
                <tr>
                    <td>Total Stok</td>
                    <td>:</td>
                    <td>{{ $data->sum('stok') }} Buku</td>
                </tr>

            @elseif($type == 'user')
                <tr>
                    <td width="35%">Total Akun</td>
                    <td width="3%">:</td>
                    <td>{{ $data->count() }} Akun</td>
                </tr>
                <tr>
                    <td>Total Email Terdaftar</td>
                    <td>:</td>
                    <td>{{ $data->whereNotNull('email')->count() }} Email</td>
                </tr>
                <tr>
                    <td>Total Admin</td>
                    <td>:</td>
                    <td>{{ $data->where('role', '1')->count() }} Akun</td>
                </tr>
                <tr>
                    <td>Total User Biasa</td>
                    <td>:</td>
                    <td>{{ $data->where('role', '0')->count() }} Akun</td>
                </tr>
            @elseif($type == 'penulis')
                <tr>
                    <td width="35%">Total Penulis</td>
                    <td width="3%">:</td>
                    <td>{{ $data->count() }} Penulis</td>
                </tr>
            @elseif($type == 'kategori')
                <tr>
                    <td width="35%">Total Kategori</td>
                    <td width="3%">:</td>
                    <td>{{ $data->count() }} Kategori</td>
                </tr>
            @elseif($type == 'tag')
                <tr>
                    <td width="35%">Total Tagar</td>
                    <td width="3%">:</td>
                    <td>{{ $data->count() }} Tagar</td>
                </tr>
            @elseif($type == 'denda')
                <tr>
                    <td width="35%">Total Member Kena Denda</td>
                    <td width="3%">:</td>
                    <td>{{ $data->count() }} Orang</td>
                </tr>
                <tr>
                    <td>Total Tagihan</td>
                    <td>:</td>
                    <td>Rp {{ number_format($data->sum('total_tagihan'), 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Rata-rata Denda</td>
                    <td>:</td>
                    <td>Rp {{ number_format($data->avg('total_tagihan'), 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Terlambat Terparah</td>
                    <td>:</td>
                    <td>{{ $data->max('hari_telat') }} Hari</td>
                </tr>
            @elseif($type == 'history_denda')
                <tr>
                    <td width="35%">Total Pembayaran</td>
                    <td width="3%">:</td>
                    <td>{{ $data->count() }} Transaksi</td>
                </tr>
                <tr>
                    <td>Total Uang Denda</td>
                    <td>:</td>
                    <td>Rp {{ number_format($data->sum('nominal_denda'), 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Rata-rata Bayar</td>
                    <td>:</td>
                    <td>Rp {{ number_format($data->avg('nominal_denda'), 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Denda Tertinggi</td>
                    <td>:</td>
                    <td>Rp {{ number_format($data->max('nominal_denda'), 0, ',', '.') }}</td>
                </tr>
            @elseif($type == 'pinjaman')
                <tr>
                    <td width="35%">Total Transaksi</td>
                    <td width="3%">:</td>
                    <td>{{ $data->count() }}</td>
                </tr>
                <tr>
                    <td>Sedang Dipinjam</td>
                    <td>:</td>
                    <td>{{ $data->where('status', 'dipinjam')->count() }} Buku</td>
                </tr>
                <tr>
                    <td>Sudah Dikembalikan</td>
                    <td>:</td>
                    <td>{{ $data->where('status', 'dikembalikan')->count() }} Buku</td>
                </tr>
                <tr>
                    <td>Terlambat</td>
                    <td>:</td>
                    <td>{{ $data->where('status', 'terlambat')->count() }} Buku</td>
                </tr>
            @endif
        </table>
    </div>
    <!-- INFO -->
    <div class="info-box">
        <table>
            <tr>
                <td width="20%"><strong>Tipe Laporan</strong></td>
                <td width="2%">:</td>
                <td>{{ strtoupper($type) }}</td>

                <td width="20%"><strong>Tanggal Cetak</strong></td>
                <td width="2%">:</td>
                <td>{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y, H:i:s') }} WIB</td>
            </tr>
        </table>
    </div>

    <!-- TABLE -->
    <table class="main-table">
        <thead>
            @if($type == 'buku')
                <tr>
                    <th width="6%">No</th>
                    <th>Judul Buku</th>
                    <th>Penulis</th>
                    <th>Kategori</th>
                    <th width="12%">Stok</th>
                </tr>
            @elseif($type == 'user')
                <tr>
                    <th width="6%">No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th width="18%">Role</th>
                </tr>
            @elseif($type == 'penulis')
                <tr>
                    <th width="10%">No</th>
                    <th>Nama Penulis</th>
                    <th>Karya Buku</th>
                </tr>
            @elseif($type == 'kategori')
                <tr>
                    <th width="10%">No</th>
                    <th>Nama Kategori</th>
                    <th>Buku Terikat</th>
                </tr>
            @elseif($type == 'tag')
                <tr>
                    <th width="10%">No</th>
                    <th>Nama Kategori</th>
                    <th>Buku Terikat</th>
                </tr>
            @elseif($type == 'denda')
                <tr>
                    <th>No</th>
                    <th>Nama Member</th>
                    <th>Judul Buku</th>
                    <th>Hari Telat</th>
                    <th>Total Denda</th>
                </tr>
            @elseif($type == 'history_denda')
                <tr>
                    <th>No</th>
                    <th>Nama User</th>
                    <th>Ref</th>
                    <th>Hari Telat</th>
                    <th>Total Denda</th>
                    <th>Tanggal Bayar</th>
                </tr>
            @elseif($type == 'pinjaman')
                <tr>
                    <th>No</th>
                    <th>Nama User</th>
                    <th>Judul Buku</th>
                    <th>Status</th>
                    <th>Tanggal Pinjam</th>
                    <th>Jatuh Tempo</th>
                </tr>
            @endif
        </thead>

        <tbody>
            @foreach($data as $key => $item)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>

                    @if($type == 'buku')
                        <td>{{ $item->judul }}</td>
                        <td>{{ $item->penulis->nama ?? 'Anonim' }}</td>
                        <td>{{ $item->kategori->nama ?? 'Umum' }}</td>
                        <td class="text-center">{{ $item->stok }}</td>
                    @elseif($type == 'user')
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td class="text-center">{{ $item->role == 1 ? 'ADMIN' : 'USER' }}</td>
                    @elseif($type == 'penulis')
                        <td>{{ $item->nama }}</td>
                        <td class="text-center">{{ $item->buku_count }} Buku</td>
                    @elseif($type == 'kategori')
                        <td>{{ $item->nama }}</td>
                        <td class="text-center">{{ $item->buku_count }} Buku</td>
                    @elseif($type == 'tag')
                        <td>{{ $item->nama }}</td>
                        <td class="text-center">{{ $item->buku_count }} Buku</td>
                    @elseif($type == 'denda')
                        <td>{{ $item->nama_member }}</td>
                        <td>{{ $item->judul_buku }}</td>
                        <td class="text-center">{{ $item->hari_telat }} Hari</td>
                        <td>Rp {{ number_format($item->total_tagihan, 0, ',', '.') }}</td>
                    @elseif($type == 'history_denda')
                        <td>{{ $item->nama_user }}</td>
                        <td>#ID-{{ $item->id_peminjaman }}</td>
                        <td class="text-center">{{ $item->hari_telat }} Hari</td>
                        <td>Rp {{ number_format($item->nominal_denda, 0, ',', '.') }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y H:i') }}</td>
                    @elseif($type == 'pinjaman')
                        <td>{{ $item->nama_user }}</td>
                        <td>{{ $item->judul_buku }}</td>
                        <td class="text-center">{{ strtoupper($item->status) }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->format('d/m/Y') }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- SIGNATURE -->
    <table class="signature">
        <tr>
            <td>
                Mengetahui,<br>
                {{ $name }}
            </td>
        </tr>
        <tr>
            <td class="signature-space"></td>
        </tr>
        <tr>
            <td>
                ____________________
            </td>
        </tr>
    </table>

    <!-- FOOTER -->
    <div class="footer clearfix">
        <div class="footer-left">
            Sistem Perpustakaan Digital: JOKOPUS
        </div>

        <div class="footer-right">
            Dicetak otomatis pada {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y, H:i:s') }} WIB
        </div>
    </div>

</body>

</html>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: white;
            margin: 0;
            padding: 30px;
            color: #e2e8f0;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background: linear-gradient(rgba(10, 14, 20, 0.6), rgba(10, 14, 20, 0.6)) background-position: center;
            background: black;
            border-radius: 14px;
            padding: 30px;
            border: 1px solid #137fec;
            box-shadow: 0 0 20px rgba(19, 127, 236, 0.3);
        }

        .logo {
            font-size: 52px;
            font-weight: 800;
            color: #00d2ff;
            text-shadow: 0 0 10px rgba(0, 210, 255, 0.5);
            letter-spacing: 1px;
        }

        .status-badge {
            background: #137fec;
            color: #fff;
            padding: 6px 16px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            box-shadow: 0 0 10px rgba(19, 127, 236, 0.5);
        }

        h2 {
            margin: 20px 0 5px;
            font-size: 22px;
            color: #ffffff;
        }

        .subtitle {
            font-size: 18px;
            color: #94a3b8;
            margin-bottom: 25px;
        }

        .card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(19, 127, 236, 0.2);
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            font-size: 13px;
            color: #00d2ff;
            background: rgba(19, 127, 236, 0.1);
            padding: 12px 16px;
        }

        td {
            padding: 12px 16px;
            font-size: 14px;
            color: #cbd5e1;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .status-success {
            color: #4ade80;
            font-weight: 700;
            text-shadow: 0 0 8px rgba(74, 222, 128, 0.4);
        }

        .note-box {
            background: rgba(19, 127, 236, 0.1);
            border-left: 4px solid #00d2ff;
            padding: 18px;
            border-radius: 8px;
            font-size: 14px;
        }
        
        .admin-box {
            background: rgba(19, 127, 236, 0.1);
            border-left: 4px solid #00d2ff;
            padding: 18px;
            border-radius: 8px;
            font-size: 14px;
            margin-top: 10px;
        }

        .note-title {
            color: #00d2ff;
            font-weight: 700;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div class="logo">JOKOPUS</div>
        </div>

        <h2>{{ $peminjaman->buku->judul }}</h2>
        <p class="subtitle">
            Pembayaran denda berhasil dilunaskan senilai <span style="color: #00d2ff;">Rp.
                {{ number_format($denda, 0, ',', '.') }}</span> untuk<br>Buku:<span style="color: #00d2ff;">
            {{ $peminjaman->buku->judul }}</span>
        </p>

        <div class="card">
            <table>
                <tr>
                    <th>ID Transaksi</th>
                    <td>#{{ $peminjaman->id }}{{ time() }}</td>
                </tr>
                <tr>
                    <th>Nama Buku</th>
                    <td>" {{ $peminjaman->buku->judul }} "</td>
                </tr>
                <tr>
                    <th>Tanggal Lunas</th>
                    <td>{{ now()->timezone('Asia/Jakarta')->format('d M Y H:i') }} WIB</td>
                </tr>
                <tr>
                    <th>Keterlambatan</th>
                    <td>{{ $hari_telat }} Hari</td>
                </tr>
                <tr>
                    <th>Total Bayar</th>
                    <td style="font-size: 16px; color: #f8fafc; font-weight: 700;">Rp.
                        {{ number_format($denda, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td class="status-success">SUCCESS / PAID</td>
                </tr>
            </table>
        </div>

        <div class="note-box">
            <div class="note-title">CATATAN DARI ADMIN</div>
            <div>
                Buku "{{ $peminjaman->buku->judul }}" telah di kembalikan, denda dilunaskan,
                Jangan sampai terlambat lagi, ya!
            </div>
        </div>
        
        <div class="admin-box">
            <div>
                Admin yang menyelesaikan: {{ $admin }}
            </div>
        </div>
    </div>

</body>

</html>
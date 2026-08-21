<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bulanan Pengajuan Alat - {{ $namaBulan }} {{ $tahun }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            color: #2d2d2d;
            background: #fff;
        }

        /* ===== HEADER ===== */
        .header {
            text-align: center;
            border-bottom: 3px solid #004DB4;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .header .logo-row {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 4px;
        }
        .header h2 {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #004DB4;
        }
        .header p {
            font-size: 9px;
            color: #555;
        }
        .header .doc-title {
            font-size: 14px;
            font-weight: bold;
            color: #222;
            margin-top: 6px;
            text-transform: uppercase;
        }
        .header .doc-subtitle {
            font-size: 11px;
            color: #004DB4;
            font-weight: bold;
        }

        /* ===== META INFO ===== */
        .meta-box {
            background: #f4f7ff;
            border: 1px solid #d0ddf7;
            border-radius: 4px;
            padding: 8px 12px;
            margin-bottom: 14px;
            display: flex;
            justify-content: space-between;
        }
        .meta-box .meta-item {
            font-size: 9px;
        }
        .meta-box .meta-item strong {
            display: block;
            font-size: 10px;
            color: #004DB4;
        }

        /* ===== TABLE ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5px;
        }
        thead {
            background-color: #004DB4;
            color: white;
        }
        thead th {
            padding: 6px 5px;
            text-align: center;
            font-weight: bold;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border: 1px solid #003a8c;
        }
        tbody tr:nth-child(even) {
            background-color: #f0f5ff;
        }
        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }
        tbody td {
            padding: 5px 5px;
            border: 1px solid #d4d4d4;
            vertical-align: top;
        }
        tbody td.center {
            text-align: center;
        }
        .badge-finished {
            background-color: #004DB4;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7.5px;
            font-weight: bold;
        }
        .badge-decline {
            background-color: #dc3545;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7.5px;
            font-weight: bold;
        }
        .badge-pending {
            background-color: #ffc107;
            color: #222;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7.5px;
            font-weight: bold;
        }
        .alat-list {
            margin: 0;
            padding-left: 12px;
        }
        .alat-list li {
            font-size: 8px;
            margin-bottom: 1px;
        }

        /* ===== SUMMARY ===== */
        .summary-box {
            margin-top: 14px;
            background: #f4f7ff;
            border: 1px solid #d0ddf7;
            border-radius: 4px;
            padding: 8px 12px;
        }
        .summary-box h4 {
            font-size: 10px;
            color: #004DB4;
            margin-bottom: 6px;
            font-weight: bold;
        }
        .summary-grid {
            display: flex;
            gap: 20px;
        }
        .summary-item {
            font-size: 9px;
        }
        .summary-item .count {
            font-size: 18px;
            font-weight: bold;
            color: #004DB4;
            display: block;
            line-height: 1.1;
        }

        /* ===== FOOTER / SIGNATURE ===== */
        .signature-section {
            margin-top: 28px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            width: 42%;
        }
        .signature-box p {
            font-size: 9px;
            color: #444;
        }
        .signature-box .sign-name {
            font-weight: bold;
            font-size: 10px;
            margin-top: 50px;
            border-top: 1px solid #999;
            padding-top: 4px;
        }
        .signature-box .sign-title {
            font-size: 8.5px;
            color: #666;
        }

        /* ===== PAGE INFO ===== */
        .page-footer {
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 6px;
            font-size: 8px;
            color: #888;
            text-align: center;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            color: #888;
            font-size: 11px;
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <div class="header">
        <h2>Politeknik Negeri Banyuwangi</h2>
        <p>Jl. Raya Jember Km. 13 Labanasem, Kabat, Banyuwangi - 68461</p>
        <div class="doc-title">Laporan Bulanan Pengajuan Alat</div>
        <div class="doc-subtitle">{{ $namaBulan }} {{ $tahun }}</div>
    </div>

    <!-- META INFO -->
    <div class="meta-box">
        <div class="meta-item">
            <strong>Periode</strong>
            {{ $namaBulan }} {{ $tahun }}
        </div>
        <div class="meta-item">
            <strong>Tanggal Cetak</strong>
            {{ now()->translatedFormat('d F Y') }}
        </div>
        <div class="meta-item">
            <strong>Total Pengajuan</strong>
            {{ $pengajuans->count() }} pengajuan
        </div>
        <div class="meta-item">
            <strong>Dicetak oleh</strong>
            {{ auth()->user()->name }} ({{ auth()->user()->role->role }})
        </div>
    </div>

    <!-- DATA TABLE -->
    @if($pengajuans->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width:3%">No</th>
                <th style="width:13%">Nama Mahasiswa</th>
                <th style="width:18%">Nama Kegiatan</th>
                <th style="width:9%">Tgl Pengajuan</th>
                <th style="width:9%">Tgl Pinjam</th>
                <th style="width:9%">Tgl Kembali</th>
                <th style="width:22%">Alat yang Dipinjam</th>
                <th style="width:7%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengajuans as $idx => $p)
            <tr>
                <td class="center">{{ $idx + 1 }}</td>
                <td>{{ $p->mahasiswa ? $p->mahasiswa->nama : '-' }}</td>
                <td>{{ $p->nama_kegiatan }}</td>
                <td class="center">{{ $p->created_at->format('d/m/Y') }}</td>
                <td class="center">{{ \Carbon\Carbon::parse($p->tanggal_peminjaman)->format('d/m/Y') }}</td>
                <td class="center">{{ \Carbon\Carbon::parse($p->tanggal_pengembalian)->format('d/m/Y') }}</td>
                <td>
                    @if($p->details && $p->details->count() > 0)
                    <ul class="alat-list">
                        @foreach($p->details as $d)
                        <li>{{ $d->detail_alat ? $d->detail_alat->nama : 'Alat tidak ditemukan' }} ({{ $d->qty }} unit)</li>
                        @endforeach
                    </ul>
                    @else
                    <span style="color:#999;">-</span>
                    @endif
                </td>
                <td class="center">
                    @php $st = strtolower($p->status); @endphp
                    @if($st == 'finished')
                        <span class="badge-finished">FINISHED</span>
                    @elseif($st == 'decline')
                        <span class="badge-decline">DECLINE</span>
                    @else
                        <span class="badge-pending">{{ strtoupper($p->status) }}</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- SUMMARY -->
    <div class="summary-box">
        <h4>Ringkasan</h4>
        <div class="summary-grid">
            <div class="summary-item">
                <span class="count">{{ $pengajuans->count() }}</span>
                Total Pengajuan
            </div>
            <div class="summary-item">
                <span class="count" style="color:#28a745;">{{ $pengajuans->where('status','finished')->count() }}</span>
                Selesai
            </div>
            <div class="summary-item">
                <span class="count" style="color:#dc3545;">{{ $pengajuans->where('status','decline')->count() }}</span>
                Ditolak
            </div>
            <div class="summary-item">
                <span class="count" style="color:#ffc107;">{{ $pengajuans->whereNotIn('status',['finished','decline'])->count() }}</span>
                Lainnya
            </div>
        </div>
    </div>

    @else
    <div class="no-data">Tidak ada data pengajuan untuk periode {{ $namaBulan }} {{ $tahun }}.</div>
    @endif

    <!-- SIGNATURE -->
    <div class="signature-section">
        <div class="signature-box">
            <p>Dibuat oleh,</p>
            <div class="sign-name">{{ auth()->user()->name }}</div>
            <div class="sign-title">{{ auth()->user()->role->role }}</div>
        </div>
        <div class="signature-box">
            <p>Mengetahui,<br>Kepala Sub Bagian Humas</p>
            <div class="sign-name">.................................</div>
            <div class="sign-title">NIP. ...............................</div>
        </div>
    </div>

    <!-- PAGE FOOTER -->
    <div class="page-footer">
        Dokumen ini dicetak otomatis oleh sistem SIPMAS &mdash; Sistem Informasi Peminjaman Alat &mdash; Politeknik Negeri Banyuwangi
    </div>

</body>
</html>

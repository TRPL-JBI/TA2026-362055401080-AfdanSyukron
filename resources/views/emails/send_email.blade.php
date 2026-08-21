<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data['title'] }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; }
        .email-wrapper { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .email-header { background-color: #5e72e4; color: #ffffff; padding: 30px 20px; text-align: center; }
        .email-header h1 { margin: 0; font-size: 24px; font-weight: 600; }
        .email-body { padding: 30px; color: #333333; line-height: 1.6; }
        .status-badge { display: inline-block; padding: 8px 16px; border-radius: 50px; font-size: 14px; font-weight: bold; color: #fff; background-color: {{ isset($data['color']) ? $data['color'] : '#11cdef' }}; }
        .details-table { width: 100%; border-collapse: collapse; margin-top: 20px; margin-bottom: 20px; }
        .details-table th, .details-table td { padding: 12px; border-bottom: 1px solid #eeeeee; text-align: left; }
        .details-table th { width: 40%; color: #8898aa; font-weight: 600; font-size: 13px; text-transform: uppercase; }
        .details-table td { font-weight: 500; }
        .email-footer { background-color: #f8f9fe; padding: 20px; text-align: center; color: #8898aa; font-size: 12px; }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <h1>SIPMAS Poliwangi</h1>
        </div>
        <div class="email-body">
            @if(isset($data['nama']))
            <p>Halo, <strong>{{ $data['nama'] }}</strong></p>
            @endif
            
            <p>{{ $data['body'] }}</p>

            <div style="text-align: center; margin: 30px 0;">
                <span class="status-badge">{{ isset($data['status']) ? $data['status'] : $data['title'] }}</span>
            </div>

            @if(isset($data['kegiatan']))
            <table class="details-table">
                <tr>
                    <th>Nama Kegiatan</th>
                    <td>{{ $data['kegiatan'] }}</td>
                </tr>
                <tr>
                    <th>Tanggal Peminjaman</th>
                    <td>{{ \Carbon\Carbon::parse($data['tgl_pinjam'])->translatedFormat('d F Y') }}</td>
                </tr>
                <tr>
                    <th>Tanggal Pengembalian</th>
                    <td>{{ \Carbon\Carbon::parse($data['tgl_kembali'])->translatedFormat('d F Y') }}</td>
                </tr>
            </table>
            @endif

            <p style="margin-top: 30px; font-size: 14px;">Silakan cek selengkapnya di sistem Peminjaman Alat SIPMAS Poliwangi.</p>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} SIPMAS Politeknik Negeri Banyuwangi. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

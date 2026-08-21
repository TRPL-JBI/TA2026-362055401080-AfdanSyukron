<?php

namespace App\Http\Controllers;

use App\Mail\SendEmail;
use App\Models\Alat;
use App\Models\DetailPengajuan;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PengajuanController extends Controller
{
    public function index()
    {
        // Fitur Penghitungan Stok Alat (Hanya tampil di Sidebar "Pengajuan Alat")
        $alat = Alat::withSum(['detailPengajuans as total_dipakai' => function ($query) {
            $query->whereHas('pengajuan', function ($q) {
                $q->where('status', 'verified');
            });
        }], 'qty')->get();

        foreach ($alat as $a) {
            if ($a->is_maintenance) {
                $a->stok_tersedia = 0;
            } else {
                $a->stok_tersedia = $a->stok - ($a->total_dipakai ?? 0);
            }
        }

        return view('pengajuan.index', compact('alat'));
    }

    public function list()
    {
        // Fitur Daftar Pengajuan (Hanya tampil di Sidebar "Daftar Pengajuan")
        if (strtolower(auth()->user()->role->role) == 'mahasiswa') {
            $pengajuans = Pengajuan::where('user_id', auth()->user()->id)
                            ->where('status', '!=', 'finished')
                            ->orderBy('created_at', 'desc')
                            ->get();
        } else {
            $pengajuans = Pengajuan::whereNotIn('status', ['finished', 'decline'])
                            ->orderBy('created_at', 'desc')
                            ->get();
        }

        return view('pengajuan.list', compact('pengajuans'));
    }

    public function history()
    {
        // Fitur History Pengajuan (Hanya untuk mahasiswa dengan status finished)
        if (strtolower(auth()->user()->role->role) == 'mahasiswa') {
            $pengajuans = Pengajuan::where('user_id', auth()->user()->id)
                            ->where('status', 'finished')
                            ->orderBy('created_at', 'desc')
                            ->get();
        } else {
            $pengajuans = Pengajuan::whereIn('status', ['finished', 'decline'])
                            ->orderBy('created_at', 'desc')
                            ->get();
        }

        return view('pengajuan.history', compact('pengajuans'));
    }

    public function create()
    {
        $pengajuan = Pengajuan::all();
        
        // Penghitungan stok tersedia untuk ditampilkan di form
        $alat = Alat::withSum(['detailPengajuans as total_dipakai' => function ($query) {
            $query->whereHas('pengajuan', function ($q) {
                $q->where('status', 'verified');
            });
        }], 'qty')->get();

        foreach ($alat as $a) {
            if ($a->is_maintenance) {
                $a->stok_tersedia = 0;
            } else {
                $a->stok_tersedia = $a->stok - ($a->total_dipakai ?? 0);
            }
        }

        $detail_pengajuan = DetailPengajuan::where('pengajuan_id', 'draft-' . auth()->user()->id)->get();
        return view('pengajuan.create', compact('pengajuan', 'alat', 'detail_pengajuan'));
    }

    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'nama_kegiatan' => 'required|string|max:100',
            'tanggal_peminjaman' => 'required|date|after_or_equal:today',
            'tanggal_pengembalian' => 'required|date|after_or_equal:tanggal_peminjaman',
            'file' => 'required|mimes:pdf,doc,docx|max:10240', // Maks 10MB
            'ktm' => 'required|mimes:jpg,jpeg,png,pdf|max:5120', // Maks 5MB
        ]);

        if (!$request->has('alat')) {
            return back()->with('error', 'Pilih minimal satu alat.');
        }

        try {
            DB::beginTransaction();

            $fileName = null;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/pengajuan'), $fileName);
            }

            $ktmName = null;
            if ($request->hasFile('ktm')) {
                $fileKtm = $request->file('ktm');
                $ktmName = 'ktm_' . time() . '_' . $fileKtm->getClientOriginalName();
                $fileKtm->move(public_path('uploads/ktm'), $ktmName);
            }

            // Membuat pengajuan baru
            $pengajuan = Pengajuan::create([
                'user_id' => auth()->user()->id,
                'nama_kegiatan' => $request->nama_kegiatan,
                'tanggal_peminjaman' => $request->tanggal_peminjaman,
                'tanggal_pengembalian' => $request->tanggal_pengembalian,
                'file' => $fileName,
                'ktm' => $ktmName,
                'status' => 'pending',
            ]);

            // Update existing drafts from this user (jika ada)
            DetailPengajuan::where('pengajuan_id', 'draft-' . auth()->user()->id)
                ->update([
                    'pengajuan_id' => $pengajuan->id,
                ]);

            // Proses input alat dari checklist
            if (is_array($request->alat)) {
                foreach ($request->alat as $alat_id) {
                    $alat = Alat::withSum(['detailPengajuans as total_dipakai' => function ($query) {
                        $query->whereHas('pengajuan', function ($q) {
                            $q->where('status', 'verified');
                        });
                    }], 'qty')->findOrFail($alat_id);

                    $stokTersedia = $alat->stok - ($alat->total_dipakai ?? 0);
                    $qty = isset($request->qty[$alat_id]) ? (int) $request->qty[$alat_id] : 1;

                    // VALIDASI STOK (BACKEND)
                    if ($qty > $stokTersedia) {
                        throw new \Exception("Stok alat '{$alat->nama}' tidak mencukupi. Tersedia: {$stokTersedia}");
                    }

                    // Cek jika sudah ada (dari draft) untuk menghindari duplicate
                    $exists = DetailPengajuan::where('pengajuan_id', $pengajuan->id)
                        ->where('alat_id', $alat_id)
                        ->first();

                    if (!$exists && $qty > 0) {
                        DetailPengajuan::create([
                            'pengajuan_id' => $pengajuan->id,
                            'alat_id' => $alat_id,
                            'qty' => $qty,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('pengajuan')->with('success', 'Pengajuan berhasil dikirim.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $user = auth()->user();
        $role = strtolower($user->role->role ?? '');

        if ($role === 'mahasiswa' && $pengajuan->user_id !== $user->id) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk mengubah pengajuan ini.');
        }

        $alat = Alat::all();
        $detail_pengajuan = DetailPengajuan::where('pengajuan_id', $pengajuan->id)->get();
        return view('pengajuan.edit', compact('pengajuan', 'alat', 'detail_pengajuan'));
    }

    public function update(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $user = auth()->user();
        $role = strtolower($user->role->role ?? '');

        if ($role === 'mahasiswa' && $pengajuan->user_id !== $user->id) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk mengubah pengajuan ini.');
        }

        // Validasi input dari form
        $request->validate([
            'nama_kegiatan' => 'required|string|max:100',
            'tanggal_peminjaman' => 'required|date',
            'tanggal_pengembalian' => 'required|date|after_or_equal:tanggal_peminjaman',
            'file' => 'nullable|mimes:pdf,doc,docx|max:10240', // Maks 10MB, optional
            'ktm' => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120', // Maks 5MB, optional
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/pengajuan'), $fileName);
            $pengajuan->file = $fileName;
        }

        if ($request->hasFile('ktm')) {
            $fileKtm = $request->file('ktm');
            $ktmName = 'ktm_' . time() . '_' . $fileKtm->getClientOriginalName();
            $fileKtm->move(public_path('uploads/ktm'), $ktmName);
            $pengajuan->ktm = $ktmName;
        }

        $pengajuan->user_id = auth()->user()->id;
        $pengajuan->nama_kegiatan = $request->nama_kegiatan;
        $pengajuan->tanggal_peminjaman = $request->tanggal_peminjaman;
        $pengajuan->tanggal_pengembalian = $request->tanggal_pengembalian;
        $pengajuan->update();

        // Sync tools from checklist: delete existing and re-add
        DetailPengajuan::where('pengajuan_id', $id)->delete();

        if ($request->has('alat') && is_array($request->alat)) {
            foreach ($request->alat as $alat_id) {
                $qty = isset($request->qty[$alat_id]) ? $request->qty[$alat_id] : 1;
                if ($qty > 0) {
                    DetailPengajuan::create([
                        'pengajuan_id' => $id,
                        'alat_id' => $alat_id,
                        'qty' => $qty,
                    ]);
                }
            }
        }

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('pengajuan')->with('success', 'pengajuan berhasil diperbarui');
    }

    public function delete($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $user = auth()->user();
        $role = strtolower($user->role->role ?? '');

        if ($role === 'mahasiswa' && $pengajuan->user_id !== $user->id) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk menghapus pengajuan ini.');
        }

        // Menghapus data 
        $pengajuan->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('pengajuan')->with('success', 'Pengajuan berhasil dihapus');
    }

    public function verif($id)
    {
        try {
            $verif = Pengajuan::with('mahasiswa')->find($id);
            $verif->status = "verified";
            $verif->update();

            $data = [
                'subject' => 'SIPMAS Poliwangi - Status Pengajuan Alat',
                'title' => 'Pengajuan Alat Disetujui',
                'body' => 'Pengajuan pinjaman alat untuk kegiatan "' . $verif->nama_kegiatan . '" telah kami Setujui.',
                'nama' => $verif->mahasiswa->nama ?? 'Peminjam',
                'kegiatan' => $verif->nama_kegiatan,
                'tgl_pinjam' => $verif->tanggal_peminjaman,
                'tgl_kembali' => $verif->tanggal_pengembalian,
                'status' => 'DISETUJUI',
                'color' => '#2dce89' // Green warning color matching the dashboard
            ];

            $emailSuccess = true;
            $emailError = '';
            try {
                Mail::to($verif->mahasiswa->email)->send(new SendEmail($data));
            } catch (\Exception $e) {
                $emailSuccess = false;
                $emailError = $e->getMessage();
            }

            // WhatsApp Notification via Fonnte API
            $waSuccess = true;
            $waError = '';
            
            if ($verif->mahasiswa && $verif->mahasiswa->whatsapp && env('FONNTE_API_TOKEN')) {
                try {
                    // Pastikan nomor diawali kode negara (misal 62)
                    $target = $verif->mahasiswa->whatsapp;
                    if (substr($target, 0, 1) == '0') {
                        $target = '62' . substr($target, 1);
                    }
                    
                    $tglPinjam = \Carbon\Carbon::parse($verif->tanggal_peminjaman)->translatedFormat('d F Y');
                    $tglKembali = \Carbon\Carbon::parse($verif->tanggal_pengembalian)->translatedFormat('d F Y');
                    
                    $pesan = "*PEMBERITAHUAN SIPMAS POLIWANGI*\n\n"
                           . "Halo *{$data['nama']}*,\n\n"
                           . "Pengajuan alat untuk kegiatan *{$verif->nama_kegiatan}* telah *DISETUJUI*.\n\n"
                           . "🗓️ *Tanggal Peminjaman:* {$tglPinjam}\n"
                           . "🗓️ *Tanggal Pengembalian:* {$tglKembali}\n\n"
                           . "Silakan cek detail sepenuhnya di sistem Peminjaman Alat SIPMAS Poliwangi.";

                    $response = \Illuminate\Support\Facades\Http::withHeaders([
                        'Authorization' => env('FONNTE_API_TOKEN'),
                    ])->asForm()->post('https://api.fonnte.com/send', [
                        'target' => $target,
                        'message' => $pesan,
                    ]);
                    
                    if ($response->failed()) {
                        $waSuccess = false;
                        $waError = $response->reason() ?: 'HTTP request failed';
                    } else {
                        $responseData = $response->json();
                        if (isset($responseData['status']) && $responseData['status'] === false) {
                            $waSuccess = false;
                            $waError = isset($responseData['reason']) ? $responseData['reason'] : (isset($responseData['detail']) ? $responseData['detail'] : 'Unknown Fonnte Error');
                        }
                    }

                } catch (\Exception $e) {
                    $waSuccess = false;
                    $waError = $e->getMessage();
                }
            }

            if (!$emailSuccess && !$waSuccess && \env('FONNTE_API_TOKEN') ) {
                 // Keduanya gagal
                 return redirect()->route('pengajuan')->with('success', 'Status berhasil diperbarui, namun Email dan WA gagal dikirim.');
            } else if (!$emailSuccess) {
                 return redirect()->route('pengajuan')->with('success', 'Status berhasil diperbarui, WA terkirim, namun Email gagal: ' . $emailError);
            } else if (!$waSuccess && \env('FONNTE_API_TOKEN')) {
                 return redirect()->route('pengajuan')->with('success', 'Status berhasil diperbarui, Email terkirim, namun WA gagal: ' . $waError);
            }

            return redirect()->route('pengajuan')->with('success', 'Status berhasil diproses, notifikasi Email dan WA terkirim ke peminjam.');
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage(),
            ]);
        }
    }
    
    public function decline($id)
    {
        try {
            $decline = Pengajuan::with('mahasiswa')->find($id);
            $decline->status = "decline";
            $decline->update();

            $data = [
                'subject' => 'SIPMAS Poliwangi - Status Pengajuan Alat',
                'title' => 'Pengajuan Alat Ditolak',
                'body' => 'Mohon maaf, pengajuan pinjaman alat untuk kegiatan "' . $decline->nama_kegiatan . '" telah kami Tolak.',
                'nama' => $decline->mahasiswa->nama ?? 'Peminjam',
                'kegiatan' => $decline->nama_kegiatan,
                'tgl_pinjam' => $decline->tanggal_peminjaman,
                'tgl_kembali' => $decline->tanggal_pengembalian,
                'status' => 'DITOLAK',
                'color' => '#f5365c' // Red color for decline
            ];

            try {
                Mail::to($decline->mahasiswa->email)->send(new SendEmail($data));
            } catch (\Exception $e) {
                return redirect()->route('pengajuan')->with('success', 'Status berhasil ditolak, namun email gagal dikirim. (Error: ' . $e->getMessage() . ')');
            }

            return redirect()->route('pengajuan')->with('success', 'Status berhasil ditolak dan email pemberitahuan telah terkirim.');
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function finish($id)
    {
        try {
            $finish = Pengajuan::findOrFail($id);
            $finish->status = "finished";
            $finish->update();

            return redirect()->route('pengajuan.list')->with('success', 'Alat telah dikembalikan dan status diperbarui.');
        } catch (\Throwable $th) {
            return redirect()->route('pengajuan.list')->with('error', 'Gagal memperbarui status: ' . $th->getMessage());
        }
    }

    public function show(Request $request, $id)
    {
        $pengajuan = Pengajuan::with(['mahasiswa'])->findOrFail($id);
        $user = auth()->user();
        $role = strtolower($user->role->role ?? '');

        if ($role === 'mahasiswa' && $pengajuan->user_id !== $user->id) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk melihat pengajuan ini.');
        }

        $alat = Alat::all();
        $detail_pengajuan = DetailPengajuan::with('detail_alat')
                            ->where('pengajuan_id', $id)
                            ->get();
                            
        // Menampilkan detail pengajuan
        return view('pengajuan.show', compact('pengajuan', 'alat', 'detail_pengajuan'));
    }

    public function reportBulanan(Request $request)
    {
        // Hanya admin, staff humas, dan kepala humas yang boleh mengakses
        $role = strtolower(auth()->user()->role->role ?? '');
        if (!in_array($role, ['staff admin', 'staff humas', 'admin', 'kepala humas'])) {
            abort(403, 'Anda tidak memiliki akses ke fitur ini.');
        }

        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        $namaBulanArr = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $namaBulan = $namaBulanArr[(int)$bulan] ?? 'Tidak Diketahui';

        $pengajuans = Pengajuan::with(['mahasiswa', 'details.detail_alat'])
            ->whereYear('tanggal_peminjaman', $tahun)
            ->whereMonth('tanggal_peminjaman', $bulan)
            ->orderBy('tanggal_peminjaman', 'asc')
            ->get();

        $pdf = Pdf::loadView('pengajuan.report', compact('pengajuans', 'namaBulan', 'tahun', 'bulan'))
            ->setPaper('a4', 'landscape');

        $fileName = 'Laporan-Pengajuan-Alat-' . $namaBulan . '-' . $tahun . '.pdf';
        return $pdf->download($fileName);
    }

}

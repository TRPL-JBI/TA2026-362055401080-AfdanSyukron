<?php

namespace App\Http\Controllers;

use App\Models\DetailPengajuan;
use Illuminate\Http\Request;

class DetailPengajuanController extends Controller
{
    public function tambahAlat(Request $request)
    {
        try {
            // Validasi input dari form
            $request->validate([
                'alat' => 'required',
                'qty' => 'required|integer|min:1',
            ]);
    
            $pengajuan_id = $request->pengajuan_id ?? 'draft-' . auth()->user()->id;
            $alats = is_array($request->alat) ? $request->alat : [$request->alat];

            foreach ($alats as $alat_id) {
                // Check if already exists in the same pengajuan/draft to avoid duplicates if preferred
                // For now, just create as requested
                DetailPengajuan::create([
                    'pengajuan_id' => $pengajuan_id,
                    'alat_id' => $alat_id,
                    'qty' => $request->qty,
                ]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'berhasil',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage(),
            ]);
        }
       
    }

    public function delete($id)
    {

        $detail = DetailPengajuan::find($id);
        // Menghapus data 
        $detail->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->back()->with('success', 'detail berhasil dihapus');
    }
}
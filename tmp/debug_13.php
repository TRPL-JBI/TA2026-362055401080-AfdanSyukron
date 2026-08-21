<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DetailPengajuan;
use App\Models\Pengajuan;

$id = 13; // Lomba Renang
$p = Pengajuan::find($id);
if ($p) {
    echo "Pengajuan: " . $p->nama_kegiatan . " (ID: $id)\n";
    $details = DetailPengajuan::where('pengajuan_id', $id)->get();
    echo "Active Details: " . $details->count() . "\n";
    foreach ($details as $d) {
        echo "- Alat ID: " . $d->alat_id . " Qty: " . $d->qty . "\n";
    }
    
    $deleted_details = DetailPengajuan::onlyTrashed()->where('pengajuan_id', $id)->get();
    echo "Deleted Details: " . $deleted_details->count() . "\n";
    foreach ($deleted_details as $d) {
        echo "- (DELETED) Alat ID: " . $d->alat_id . " Qty: " . $d->qty . "\n";
    }
    
} else {
    echo "Pengajuan 13 not found.\n";
}

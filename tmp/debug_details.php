<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DetailPengajuan;
use App\Models\Pengajuan;

$lastPengajuan = Pengajuan::latest()->first();
if ($lastPengajuan) {
echo "All DetailPengajuan records:\n";
$allDetails = DetailPengajuan::withTrashed()->get();
foreach ($allDetails as $d) {
    echo "- ID: " . $d->id . " Pengajuan ID: " . $d->pengajuan_id . " Alat ID: " . $d->alat_id . " Qty: " . $d->qty . " Deleted: " . ($d->deleted_at ? 'Yes' : 'No') . "\n";
}

} else {
    echo "No pengajuan found.\n";
}

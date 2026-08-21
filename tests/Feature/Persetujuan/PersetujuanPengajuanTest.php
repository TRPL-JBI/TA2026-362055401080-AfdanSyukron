<?php

namespace Tests\Feature\Persetujuan;

use App\Models\User;
use App\Models\Alat;
use App\Models\Pengajuan;
use App\Models\DetailPengajuan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PersetujuanPengajuanTest extends TestCase
{
    use RefreshDatabase;

    public function test_stok_reservation_logic_upon_verification_decline_and_finish()
    {
        Mail::fake();
        Http::fake();

        $role = \App\Models\Role::factory()->create(['role' => 'admin']);
        $admin = User::factory()->create(['role_id' => $role->id]);
        $alat = Alat::factory()->create(['stok' => '10']);

        // 1. Create a pending pengajuan for 3 items of the tool
        $pengajuan = Pengajuan::factory()->create(['status' => 'pending']);
        DetailPengajuan::factory()->create([
            'pengajuan_id' => $pengajuan->id,
            'alat_id' => $alat->id,
            'qty' => '3'
        ]);

        // Check available stock - since it's pending, it should still be 10 (not reserved)
        $this->assertEquals(10, $this->getAvailableStock($alat->id));

        // 2. Verify the pengajuan (sets status to verified)
        $this->actingAs($admin)->get(route('pengajuan.verif', $pengajuan->id));
        
        // Check available stock - since it is verified, it should be 10 - 3 = 7 (reserved)
        $this->assertEquals(7, $this->getAvailableStock($alat->id));

        // 3. Complete/finish the pengajuan (sets status to finished)
        $this->actingAs($admin)->get(route('pengajuan.finish', $pengajuan->id));

        // Check available stock - since it is finished, it should be restored back to 10
        $this->assertEquals(10, $this->getAvailableStock($alat->id));
    }

    private function getAvailableStock($alatId)
    {
        $alat = Alat::withSum(['detailPengajuans as total_dipakai' => function ($query) {
            $query->whereHas('pengajuan', function ($q) {
                $q->where('status', 'verified');
            });
        }], 'qty')->find($alatId);

        return $alat->stok - ($alat->total_dipakai ?? 0);
    }
}

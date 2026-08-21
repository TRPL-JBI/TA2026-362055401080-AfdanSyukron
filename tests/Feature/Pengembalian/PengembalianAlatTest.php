<?php

namespace Tests\Feature\Pengembalian;

use App\Models\User;
use App\Models\Pengajuan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengembalianAlatTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_record_tool_return_successfully()
    {
        $role = \App\Models\Role::factory()->create(['role' => 'admin']);
        $admin = User::factory()->create(['role_id' => $role->id]);
        $pengajuan = Pengajuan::factory()->create(['status' => 'verified']);

        $response = $this->actingAs($admin)->get(route('pengajuan.finish', $pengajuan->id));

        $response->assertRedirect(route('pengajuan.list'));
        $response->assertSessionHas('success', 'Alat telah dikembalikan dan status diperbarui.');

        $this->assertDatabaseHas('pengajuans', [
            'id' => $pengajuan->id,
            'status' => 'finished',
        ]);
    }

    public function test_record_tool_return_fails_when_pengajuan_not_found()
    {
        $role = \App\Models\Role::factory()->create(['role' => 'admin']);
        $admin = User::factory()->create(['role_id' => $role->id]);

        $response = $this->actingAs($admin)->get(route('pengajuan.finish', 99999));

        $response->assertRedirect(route('pengajuan.list'));
        $response->assertSessionHas('error');
    }
}
